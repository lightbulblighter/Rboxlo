using System;
using System.Windows;
using System.Windows.Media.Imaging;
using System.Threading.Tasks;
using System.Drawing;

using Rboxlo.Core;
using Rboxlo.Core.Common;
using Rboxlo.Launcher.Base;
using System.Windows.Media;

namespace Rboxlo.Launcher.UI
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        private static int TotalBytes = -1;
        private static string FormattedTotalBytes = null;
        private Heart launcherHeart;

        public MainWindow(string[] applicationArgs, InternetConnection connection)
        {
            InitializeComponent();
            launcherHeart = new Heart(applicationArgs, this, connection);
        }

        /// <summary>
        /// Displays an error message
        /// </summary>
        /// <param name="message">Error message to display</param>
        public void DisplayErrorMessage(string message)
        {
            StatusText.Content = message;
            StatusProgressBar.Visibility = Visibility.Hidden;
            StatusImage.Source = new BitmapImage(Util.BuildResourceUri("Error.png"));
        }

        /// <summary>
        /// Sets progress bar indertiminationtotopn
        /// </summary>
        /// <param name="indeterminate">Is intterdmfjaafdssfdga</param>
        public void SetProgressBarIndetermination(bool indeterminate)
        {
            StatusProgressBar.IsIndeterminate = indeterminate;
        }

        /// <summary>
        /// Sets download total
        /// </summary>
        /// <param name="total">Total bytes to receive</param>
        public void SetDownloadTotal(int total)
        {
            if (DownloadSize.Visibility == Visibility.Hidden)
            {
                DownloadSize.Visibility = Visibility.Visible;
            }
            
            string formatted = Util.FormatBytes(total);

            SetProgressBarIndetermination(false);
            StatusProgressBar.Value = 0;
            DownloadSize.Content = String.Format(Translation.FetchMessage("size_indicator", false), "???", formatted);

            TotalBytes = total;
            FormattedTotalBytes = formatted;
        }

        /// <summary>
        /// Is TotalBytes null
        /// </summary>
        /// <returns>Is set or not</returns>
        public bool HasTotalSet()
        {
            return (TotalBytes != -1) && (FormattedTotalBytes != null);
        }

        /// <summary>
        /// Increments download progress by given amount
        /// </summary>
        /// <param name="bytes">Bytes to increment by</param>
        public void SetDownloadProgress(int bytes)
        {
            DownloadSize.Content = String.Format(Translation.FetchMessage("size_indicator", false), Util.FormatBytes(bytes), FormattedTotalBytes);
            StatusProgressBar.Value = (((float)bytes / (float)TotalBytes) * StatusProgressBar.Maximum);
        }
        
        /// <summary>
        /// Removes all download info
        /// </summary>
        public void RemoveDownloadInfo()
        {
            SetProgressBarIndetermination(true);
            StatusProgressBar.Value = 0;
            DownloadSize.Content = Translation.FetchMessage("size_indicator", false);
            DownloadSize.Visibility = Visibility.Hidden;
            TotalBytes = -1;
            FormattedTotalBytes = null;
        }

        /// <summary>
        /// Displays a status message
        /// </summary>
        /// <param name="message">Message to display</param>
        public void DisplayStatusMessage(string message)
        {
            StatusText.Content = message;
        }

        /// <summary>
        /// Event handler for CancelButton
        /// </summary>
        private void CancelButtonClick(object sender, RoutedEventArgs e)
        {
            WindowClosing(null, null);
        }

        /// <summary>
        /// Event handler for WindowLoaded
        /// </summary>
        private async void WindowLoaded(object sender, RoutedEventArgs e)
        {
            // Set the value of elements that do not ever change
            Title = Constants.ProjectName;
            CancelButton.Content = Translation.FetchMessage("cancel");

            // Hide download size
            DownloadSize.Visibility = Visibility.Hidden;

            // Default values
            StatusText.Content = Translation.FetchMessage("initializing");
            DownloadSize.Content = String.Format(Translation.FetchMessage("size_indicator", false), "???", "???");

            // Wait a bit to show we are alive
            await Task.Delay(1000);

            // Begin.
            launcherHeart.Beat();
        }

        /// <summary>
        /// Event handler for WindowClosing
        /// </summary>
        private void WindowClosing(object sender, System.ComponentModel.CancelEventArgs e)
        {
            Discord.RPC.Destroy();
            launcherHeart.HideTrayIcon();
            Application.Current.Shutdown();
        }
    }
}
