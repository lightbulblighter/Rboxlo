using System;
using System.Globalization;
using System.Reflection;
using Microsoft.Win32;

namespace Rboxlo.Core.Common
{
    /// <summary>
    /// General purpose methods
    /// </summary>
    public static class Util
    {
        /// <summary>
        /// Converts a string to titlecase
        /// </summary>
        /// <param name="input">String to convert</param>
        /// <returns>Titlecased string</returns>
        public static string ToTitleCase(string input)
        {
            return CultureInfo.InvariantCulture.TextInfo.ToTitleCase(input.ToLower());
        }

        /// <summary>
        /// Makes a string machine-readable, by removing all special symbols
        /// https://stackoverflow.com/a/1120407
        /// </summary>
        /// <param name="input">String to convert</param>
        /// <returns>Machine readable string</returns>
        public static string ToMachineReadable(string input)
        {
            char[] buffer = new char[input.Length];
            int idx = 0;

            foreach (char c in input)
            {
                if ((c >= '0' && c <= '9') || (c >= 'A' && c <= 'Z')
                    || (c >= 'a' && c <= 'z') || (c == '.') || (c == '_'))
                {
                    buffer[idx] = c;
                    idx++;
                }
            }

            return new string(buffer, 0, idx);
        }

        /// <summary>
        /// Adds a program to the uninstall registry
        /// </summary>
        /// <param name="icon">Sets the icon of the program in the Control Panel. Path to an ".ico" file</param>
        /// <param name="location">The path/folder where the program is being installed</param>
        /// <param name="uninstall">Command line arguments to uninstall the program</param>
        /// <param name="application">Application name</param>
        /// <param name="proper">Proper name</param>
        /// <param name="url">About page for the application. Leave as blank if no page</param>
        public static void AddUninstallOption(string icon, string location, string uninstall, string application, string proper, string url = null)
        {
            DateTime now = DateTime.Today;
            int install = Convert.ToInt32(now.ToString("yyyymmdd"));

            RegistryKey key = Registry.CurrentUser.CreateSubKey(String.Format(@"SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\{0}", application));
            key.SetValue("DisplayIcon", icon, RegistryValueKind.String);
            key.SetValue("DisplayName", proper, RegistryValueKind.String);
            key.SetValue("DisplayVersion", GetCurrentAssembly().Version, RegistryValueKind.String);
            key.SetValue("InstallDate", install, RegistryValueKind.String);
            key.SetValue("InstallLocation", location, RegistryValueKind.String);
            key.SetValue("NoModify", 1, RegistryValueKind.DWord);
            key.SetValue("NoRepair", 1, RegistryValueKind.DWord);
            key.SetValue("Publisher", proper, RegistryValueKind.String);
            key.SetValue("UninstallString", uninstall, RegistryValueKind.String);

            if (url != null)
            {
                key.SetValue("URLInfoAbout", url, RegistryValueKind.String);
            }

            key.Close();
        }

        /// <summary>
        /// Adds an application's URI protocol
        /// </summary>
        /// <param name="protocol">Application name or "protocol", as the "game" in "game://args"</param>
        /// <param name="path">Direct application path</param>
        /// <param name="description">Application description</param>
        public static void AddURIProtocol(string protocol, string path, string description)
        {
            RegistryKey uri = Registry.ClassesRoot.CreateSubKey(protocol);
            uri.SetValue(null, description);
            uri.SetValue("URL Protocol", String.Empty);
            uri.Close();

            RegistryKey shell = Registry.ClassesRoot.CreateSubKey(String.Format(@"{0}\Shell\open\command", protocol));
            shell.SetValue(null, String.Format("\"{0}\" \"%1\"", path));
            shell.Close();
        }

        /// <summary>
        /// Gets current
        /// </summary>
        /// <returns>Current Assembly</returns>
        public static AssemblyName GetCurrentAssembly()
        {
            return Assembly.GetExecutingAssembly().GetName();
        }

        /// <summary>
        /// Builds a URI for a given resource
        /// </summary>
        /// <param name="resourceName">Name of resource</param>
        /// <returns>Built URI</returns>
        public static Uri BuildResourceUri(string resourceName)
        {
            return new Uri(String.Format("pack://application:,,,/{0};component/Resources/{1}", GetCurrentAssembly().Name, resourceName));
        }

        /// <summary>
        /// Deletes an uninstall option from registry
        /// </summary>
        /// <param name="application">Name of application</param>
        public static void RemoveUninstallOption(string application)
        {
            Registry.ClassesRoot.DeleteSubKeyTree(String.Format(@"SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\{0}", application));
        }

        /// <summary>
        /// Deletes a URI protocol
        /// </summary>
        /// <param name="protocol">Protocol to remove</param>
        public static void RemoveURIProtocol(string protocol)
        {
            Registry.ClassesRoot.DeleteSubKey(protocol);
        }

        /// <summary>
        /// Formats bytes to human readable form
        /// </summary>
        /// <param name="bytes">Bytes</param>
        /// <param name="decimals">Max amount of decimals to display</param>
        /// <param name="bi">Bisexual form</param>
        public static string FormatBytes(int bytes, int decimals = 2, bool bi = true)
        {
            if (bytes == 0)
            {
                return "";
            }

            int k = bi ? 1024 : 1000;
            int dm = decimals < 0 ? 0 : decimals;
            string[] sizes = bi ? new string[] { "Bytes", "KiB", "MiB", "GiB", "TiB", "PiB" } : new string[] { "Bytes", "KB", "MB", "GB", "TB", "PB" };

            int idx = Convert.ToInt32(Math.Floor(Math.Log(bytes) / Math.Log(k)));
            return String.Format("{0} {1}", ((bytes / Math.Pow(k, idx)).ToString(String.Format("N{0}", dm))), sizes[idx]);
        }
    }
}
