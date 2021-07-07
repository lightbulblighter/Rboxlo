using System;
using System.Collections.Generic;
using System.Net;
using System.ComponentModel;
using System.Threading.Tasks;
using Rboxlo.Launcher.UI;

namespace Rboxlo.Launcher.Base
{
    /// <summary>
    /// Downloads stuff. Shows progress bars. It makes progress bars, very good.
    /// </summary>
    public class Downloader : IDisposable
    {
        private static MainWindow window;
        private static Dictionary<string, string> files;
        private static string laciwid = null;
        private static bool completed = false;

        private static bool disposedValue;

        /// <summary>
        /// Class constructor for Download
        /// </summary>
        /// <param name="_window">Current MainWindow</param>
        /// <param name="_files">List of files to download</param>
        /// <param name="_laciwid">If license required, current LACIWID to download with</param>
        public Downloader(MainWindow _window, Dictionary<string, string> _files, string _laciwid = null)
        {
            window = _window;
            files = _files;
            laciwid = _laciwid;
        }

        /// <summary>
        /// Starts completing all tasks
        /// </summary>
        public async Task Run()
        {
            foreach (KeyValuePair<string, string> task in files)
            {
                string url = task.Key;
                string location = task.Value;

                WebClient client = new WebClient();

                if (laciwid != null)
                {
                    client.Headers.Add("LACIWID", laciwid);
                }

                client.DownloadProgressChanged += new DownloadProgressChangedEventHandler(DownloadProgressChanged);
                client.DownloadFileCompleted += new AsyncCompletedEventHandler(DownloadFileCompleted);
                client.DownloadFileAsync(new Uri(url), location);

                while (!completed)
                {
                    await Task.Delay(200);
                }

                completed = false;
            }

            return;
        }

        /// <summary>
        /// Download progress changed event
        /// </summary>
        private static void DownloadProgressChanged(object sender, DownloadProgressChangedEventArgs e)
        {
            if (!window.HasTotalSet())
            {
                window.SetDownloadTotal(Convert.ToInt32(e.TotalBytesToReceive));
            }

            window.SetDownloadProgress(Convert.ToInt32(e.BytesReceived));
        }

        /// <summary>
        /// Download completed event
        /// </summary>
        private static void DownloadFileCompleted(object sender, AsyncCompletedEventArgs e)
        {
            completed = true;
        }

        /// <summary>
        /// Disposes of Downloader
        /// </summary>
        protected virtual void Dispose(bool disposing)
        {
            if (!disposedValue)
            {
                window = null;
                files = null;
                disposedValue = true;
            }
        }

        public void Dispose()
        {
            // Do not change this code. Put cleanup code in 'Dispose(bool disposing)' method
            Dispose(disposing: true);
            GC.SuppressFinalize(this);
        }
    }
}
