using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using System.IO;
using System.Net;
using System.Drawing;
using System.Windows.Interop;
using System.Diagnostics;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using System.Security.Cryptography;

namespace RboxloLauncher
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        private static string LocalApplicationData = Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData);
        private static string CurrentWorkingDirectory = Directory.GetCurrentDirectory();
        private static string BaseUrl = "http://localhost";
        private WebClient InternetConnection = new WebClient();

        public MainWindow()
        {
            InitializeComponent();
            ConnectRboxlo();
        }

        private string GetSha256Hash(string fileName)
        {
            FileStream filestream;
            SHA256 mySHA256 = SHA256Managed.Create();
            filestream = new FileStream(fileName, FileMode.Open);
            filestream.Position = 0;
            byte[] hashValue = mySHA256.ComputeHash(filestream);
            filestream.Close();

            return BitConverter.ToString(hashValue).Replace("-", String.Empty).ToLower();
        }

        private void FailSetup(string message)
        {
            StatusText.Content = message;
            StatusProgressBar.Visibility = Visibility.Hidden;
            StatusImage.Source = Imaging.CreateBitmapSourceFromHIcon(SystemIcons.Error.Handle, Int32Rect.Empty, BitmapSizeOptions.FromEmptyOptions());
        }

        private void ConnectRboxlo()
        {
            bool succeeded = false;
            StatusText.Content = "Connecting to Rboxlo...";
            ServicePointManager.SecurityProtocol = SecurityProtocolType.Tls12;

            // Attempt connection
            try
            {
                var output = InternetConnection.DownloadString(BaseUrl + "/api/setup/ok");
                succeeded = (bool)JObject.Parse(output)["success"];
            }
            catch
            {
                FailSetup("Failed to connect to Rboxlo.");
            }

            if (succeeded)
            {
                // Move on to the next stage.
                InitializeRboxlo();
            }
        }

        private void InitializeRboxlo()
        {
            // See our directory
            if (CurrentWorkingDirectory != LocalApplicationData + @"\Rboxlo")
            {
                // If we aren't in LocalAppData, assume that we are installing a new copy
                // Other than that, there is no "proper" way of downloading a new launcher.
                // I could do it like Roblox does it, where the case is a new launcher for each version,
                // but I'd have to mess with the registry and url protocols 24/7.

                bool succeeded = true;

                if (Directory.Exists(LocalApplicationData + @"\Rboxlo"))
                {
                    Directory.Delete(LocalApplicationData + @"\Rboxlo", true);
                }
                Directory.CreateDirectory(LocalApplicationData + @"\Rboxlo");

                JObject details = JObject.Parse(InternetConnection.DownloadString(BaseUrl + "/api/setup/info"));
                string launcherUrl = BaseUrl + "/api/setup/files/launcher/" + (string)details["launcher"];
                InternetConnection.DownloadFile(launcherUrl, LocalApplicationData + @"\Rboxlo\RboxloLauncher.exe");

                // Verify that we got the valid launcher
                if ((string)details["launcher"] != GetSha256Hash(LocalApplicationData + @"\Rboxlo\RboxloLauncher.exe"))
                {
                    FailSetup("Failed to verify integrity of downloaded launcher.");
                    succeeded = false;
                }

                if (succeeded)
                {
                    // Start that process up with our arguments
                    StatusText.Content = "Initializing Rboxlo...";

                    ProcessStartInfo launcher = new ProcessStartInfo(LocalApplicationData + @"\Rboxlo\RboxloLauncher.exe");
                    launcher.UseShellExecute = true;
                    launcher.Arguments = String.Join(" ", GlobalVars.Arguments);

                    Process.Start(launcher);
                    Application.Current.Shutdown();
                }
            }

            if (GlobalVars.Arguments.Length > 0)
            {
                // There are some arguments, lets see them

            }
        }

        private void CancelButtonClick(object sender, RoutedEventArgs e)
        {
            Application.Current.Shutdown();
        }
    }
}
