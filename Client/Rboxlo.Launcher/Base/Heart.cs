using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Threading.Tasks;
using System.Windows.Forms;
using Newtonsoft.Json.Linq;
using Rboxlo.Core;
using Rboxlo.Core.Common;
using Rboxlo.Launcher.UI;

namespace Rboxlo.Launcher.Base
{
    /// <summary>
    /// The heart of Rboxlo.Launcher. Does everything launcher-wise.
    /// </summary>
    public class Heart : IDisposable
    {
        private static string LACIWID = null; // current laciwid if license is necessary
        private static TrayIcon Icon; // current launcher icon
        private static string[] Arguments; // app args
        private static MainWindow Window; // launcher window
        private static InternetConnection Connection; // current connection
        private static Discord.RPC RichPresence; // rich presence

        private static bool disposedValue;

        /// <summary>
        /// Class constructor for Heart
        /// </summary>
        /// <param name="arguments">App cmdline args</param>
        /// <param name="window">MainWindow</param>
        /// <param name="connection">Current InternetConnection</param>
        public Heart(string[] arguments, MainWindow window, InternetConnection connection)
        {
            Arguments = arguments;
            Connection = connection;
            Window = window;
        }

        /// <summary>
        /// Exits application. Only used for the tray icon
        /// </summary>
        private static void IconExit(object sender, EventArgs e)
        {
            Discord.RPC.Destroy();
            Icon.Destroy();

            Environment.Exit(0);
        }

        /// <summary>
        /// Toggles Discord Rich Presence
        /// </summary>
        private static void ToggleRichPresence(object sender = null, EventArgs e = null)
        {
            ContextMenu menu = Icon.GetMenu();
            menu.MenuItems[0].Checked = !menu.MenuItems[0].Checked;
            Icon.SetMenu(menu);

            Discord.RPC.SetVisiblity(menu.MenuItems[0].Checked);
        }

        /// <summary>
        /// Shows tray icon
        /// </summary>
        private static void BuildTrayIcon()
        {
            MenuItem title = new MenuItem("Rboxlo");
            title.Enabled = false;

            MenuItem toggleRPC = new MenuItem("Discord Rich Presence", ToggleRichPresence);
            toggleRPC.Checked = true;

            ContextMenu menu = new ContextMenu(new MenuItem[]
            {
                /* 0 */ title,
                /* 1 */ new MenuItem("-"),
                /* 2 */ new MenuItem("E&xit", IconExit),
                /* 3 */ toggleRPC
            });

            Icon = new TrayIcon(menu);
            Icon.Display();
        }

        /// <summary>
        /// Destroys tray icon
        /// </summary>
        public void HideTrayIcon()
        {
            if (Icon != null)
            {
                Icon.Destroy();
            }
        }

        /// <summary>
        /// Downloads and verifies file list
        /// </summary>
        /// <param name="files">Files where key is the URL of the file, and value is the file location (e.g. @/Rboxlo.Launcher.exe)</param>
        /// <param name="hashes">File hashes where key is the file location and hash is the file sha256</param>
        /// <param name="deleteEarmarks">Whether to delete "earmarked" files, e.g. files that failed the verification test</param>
        private static async Task DownloadAndVerifyFiles(Dictionary<string, string> hashes, Dictionary<string, string> files, bool deleteEarmarks = true)
        {
            // fix our paths
            foreach (KeyValuePair<string, string> file in files)
            {
                string key = file.Key;
                string location = file.Value;

                location = location.Replace("@", "");

                files[key] = Path.Combine(LauncherConstants.ApplicationFolder, location);
            }

            // download files
            using (Downloader downloader = new Downloader(Window, files, LACIWID))
            {
                Window.DisplayStatusMessage(Translation.FetchMessage("downloading"));
                await downloader.Run();
            }

            // verify files
            List<string> earmarkedFiles;
            using (FileChecker verifier = new FileChecker(hashes))
            {
                Window.DisplayStatusMessage(String.Format(Translation.FetchMessage("verifying", false)));
                await verifier.Run();

                earmarkedFiles = verifier.GetEarmarkedFiles();
            }

            if (deleteEarmarks)
            {
                // delete earmarked files
                foreach (string file in earmarkedFiles)
                {
                    File.Delete(file);
                }
            }

            return;
        }

        /// <summary>
        /// Installs Rboxlo by downloading only the launcher and adding an uninstall option to registry
        /// </summary>
        private static async void Install()
        {
            if (Directory.Exists(LauncherConstants.ApplicationFolder))
            {
                foreach (string file in Directory.GetFiles(LauncherConstants.ApplicationFolder))
                {
                    string location = new FileInfo(file).Name.ToLower();

                    if (location != "license.bin")
                    {
                        File.Delete(location);
                    }
                }
            }

            Dictionary<string, string> headers = null;
            if (LACIWID != null)
            {
                headers.Add("LACIWID", LACIWID);
            }

            string response = Connection.Get(String.Format("{0}/api/launcher/setup/manifest?application=launcher", Constants.BaseURL), headers).Data;
            string executable = null;
            JObject body = JObject.Parse(response);

            Dictionary<string, string> hashes = new Dictionary<string, string>();
            Dictionary<string, string> files = new Dictionary<string, string>();

            // create our download list
            foreach (var file in body["objects"]["files"])
            {
                string filename = file["filename"].ToString();
                string hash = file["sha256"].ToString();

                string url = String.Format("{0}/api/files/get?sha256={0}&application={0}", Constants.BaseURL, hash);
                string location = Path.Combine(LauncherConstants.ApplicationFolder, filename);

                if (filename == String.Format("{0}.exe", Util.GetCurrentAssembly().Name))
                {
                    // this is our application
                    executable = location;
                }

                hashes.Add(location, hash);
                files.Add(url, location);
            }

            if (executable == null)
            {
                // this should never happen
                Window.DisplayStatusMessage(Translation.FetchMessage("catch_all_error"));
                return;
            }

            // download files
            await DownloadAndVerifyFiles(hashes, files);

            // we downloaded and verified everything, so time to install
            Window.DisplayStatusMessage(Translation.FetchMessage("installing"));

            // add uninstall option to registry
            Util.AddUninstallOption(
                String.Format("{0},0", executable),
                LauncherConstants.ApplicationFolder,
                String.Format("\"{0}\" --uninstall", executable),
                LauncherConstants.ApplicationName,
                Constants.ProjectName,
                Constants.BaseURL
            );

            // add uri protocol to registry
            Util.AddURIProtocol(
                LauncherConstants.ApplicationName,
                executable,
                Translation.FetchMessage("app_description")
            );

            // redirect user to games page
            // will automatically close program
            RedirectToGamesPage();
        }

        /// <summary>
        /// Uninstalls current program
        /// </summary>
        private static void Uninstall()
        {
            // delete our files in registry
            Util.RemoveUninstallOption(LauncherConstants.ApplicationName);
            Util.RemoveURIProtocol(LauncherConstants.ApplicationName);

            // delete our installed clients (if installed)
            if (Directory.Exists(Path.Combine(LauncherConstants.LocalApplicationData, "Client")))
            {
                Directory.Delete(Path.Combine(LauncherConstants.LocalApplicationData, "Client"), true);
            }

            // start a handoff to kill ourselves
            ProcessStartInfo handoff = new ProcessStartInfo(Path.Combine(LauncherConstants.LocalApplicationData, "Rboxlo.Launcher.Handoff.exe"));
            handoff.Arguments = "--uninstall";
            handoff.UseShellExecute = true;

            Process.Start(handoff);

            // we're done
            // do this right after process starts so that it doesn't find it's papa and set us as its parent
            Environment.Exit(0);
        }

        /// <summary>
        /// Redirects to game page. Self-explanatory. Also kills process
        /// </summary>
        private static void RedirectToGamesPage()
        {
            Process.Start(String.Format("{0}/games/", Constants.BaseURL));
            Environment.Exit(0);
        }

        /// <summary>
        /// Disposes of Heart
        /// </summary>
        protected virtual void Dispose(bool disposing)
        {
            if (!disposedValue)
            {
                Icon.Destroy();
                Connection = null;
                LACIWID = null;
                Icon = null;
                Arguments = null;
                Window = null;

                disposedValue = true;
            }
        }

        public void Dispose()
        {
            // Do not change this code. Put cleanup code in 'Dispose(bool disposing)' method
            Dispose(disposing: true);
            GC.SuppressFinalize(this);
        }

        /// <summary>
        /// Begins Launcher process
        /// </summary>
        public void Beat()
        {
            Step1();
        }

        /// <summary>
        /// Step 1 of launcher process
        /// 
        /// Step 1:
        /// - tries to connect to RBOXLO. If failed, it errors.
        /// - proceeds to step 2;
        /// </summary>
        private static async void Step1()
        {
            Window.DisplayStatusMessage(Translation.FetchMessage("connecting"));

            await Task.Delay(500); // we r alive!

            if (!Connection.OK())
            {
                Window.DisplayErrorMessage(Translation.FetchMessage("http_failure"));
                return;
            }

            Step2();
        }

        /// <summary>
        /// Step 2 of launcher process
        /// 
        /// Step 2:
        /// - sees if launcher needs a license. If so, gets LACIWID.
        /// - proceeds to step 3;
        /// </summary>
        private static void Step2()
        {
            bool worked = true; // blocking

            using (LicenseValidator checker = new LicenseValidator(LauncherConstants.CurrentApp, LauncherConstants.UserAgent, Connection))
            {
                worked = false;

                if (checker.Required())
                {
                    Window.DisplayStatusMessage(Translation.FetchMessage("license_validating", false));
                    string licensePath = Path.Combine(LauncherConstants.ApplicationFolder, "license.bin");

                    // will keep showing prompt forever until license successfully verifies
                    while (LACIWID == null)
                    {
                        if (File.Exists(licensePath))
                        {
                            string content = File.ReadAllText(licensePath);
                            LicenseResponse result = checker.Check(content);

                            if (result.Success)
                            {
                                LACIWID = result.LACIWID;
                                worked = true;
                            }
                            else
                            {
                                File.Delete(licensePath);
                            }
                        }

                        LicenseSelector selector = new LicenseSelector();
                        selector.ShowDialog();
                    }
                }
            }

            if (worked)
            {
                Step3();
            }
        }

        /// <summary>
        /// Step 3 of launcher process:
        /// 
        /// Step 3:
        /// -[1] current argument length == 0
        ///      -TRUE: checks current application directory == wherei t should be
        ///             -TRUE: goes to game page
        ///             -FALSE: installs
        /// -[2] argument[0] is uninstall: UNINSTALL
        /// -[3] goes to step4 to parse argumentsmxhg
        /// </summary>
        private static void Step3()
        {
            if (Arguments.Length == 0)
            {
                if (LauncherConstants.CurrentWorkingDirectory != LauncherConstants.ApplicationFolder)
                {
                    Install();
                    return;
                }
                else
                {
                    RedirectToGamesPage();
                    return;
                }
            }

            if (Arguments[0] == "--uninstall")
            {
                Uninstall();
                return;
            }
        }
    }
}
