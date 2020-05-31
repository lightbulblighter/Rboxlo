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
            StatusText.Content = "Connecting to Rboxlo...";
            ServicePointManager.SecurityProtocol = SecurityProtocolType.Tls12;
            
            // Attempt connect
            try
            {
                InternetConnection.OpenRead("https://www.rboxlo.xyz/setup/ok");
            }
            catch
            {
                StatusText.Content = "Failed to connect to Rboxlo.";
                StatusProgressBar.Visibility = Visibility.Hidden;
                StatusImage.Source = Imaging.CreateBitmapSourceFromHIcon(SystemIcons.Error.Handle, Int32Rect.Empty, BitmapSizeOptions.FromEmptyOptions());
            }
        }

        private void CancelButtonClick(object sender, RoutedEventArgs e)
        {
            Application.Current.Shutdown();
        }
    }
}
