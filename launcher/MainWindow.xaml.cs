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

namespace RboxloLauncher
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        private static string LocalApplicationData = Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData);
        private static string CurrentWorkingDirectory = Directory.GetCurrentDirectory();
        private WebClient InternetConnection = new WebClient();

        public MainWindow()
        {
            InitializeComponent();
            InitializeRboxlo();
        }

        private void InitializeRboxlo()
        {
            StatusText.Content = "Initializing Rboxlo...";

            // See if we are connected to the internet; if we are not, we cannot do anything.
            try
            {
                InternetConnection.OpenRead("http://google.com/generate_204");
            }
            catch
            {
                StatusText.Content = "Rboxlo requires an internet connection.";
                StatusProgressBar.Visibility = Visibility.Hidden;
                StatusImage.Source = Imaging.CreateBitmapSourceFromHIcon(SystemIcons.Error.Handle, Int32Rect.Empty, BitmapSizeOptions.FromEmptyOptions());
            }

            // Set our internet security protocol to Tls12 for backwards compatibility with Windows 7, etc.
            ServicePointManager.SecurityProtocol = SecurityProtocolType.Tls12;

            // Check our current directory
            // If we aren't in %localappdata%\Rboxlo, then lets download the latest launcher



            // Check the arguments provided.
            if (GlobalVars.Arguments.Length != 0)
            {
                Process.Start("https://www.rboxlo.xyz/games");
                Application.Current.Shutdown();
            }

            string uri = GlobalVars.Arguments[0].Replace("rboxlo", "").Replace(":", "").Replace("/", "").Replace("?", "").Trim();
            string[] arguments;

            try
            {
                arguments = uri.Split('+');
            }
            catch
            {
                // We cannot split it.
                Process.Start("https://www.rboxlo.xyz/games");
                Application.Current.Shutdown();
            }

            // A check to compare our current working directory with %localappdata%\Rboxlo, and if our filename is "RboxloLauncher.exe".
            // This helps with launcher auto-updating.

            // [cont] we download RboxloLauncher.exe from https://www.rboxlo.xyz/setup/ and run it as a separate process, so that we can always get the latest launcher.
            if (CurrentWorkingDirectory != LocalApplicationData + "\\Rboxlo")
            {
                
            }
        }

        private void CancelButtonClick(object sender, RoutedEventArgs e)
        {
            Application.Current.Shutdown();
        }
    }
}
