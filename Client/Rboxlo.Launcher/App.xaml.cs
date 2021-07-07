using System.Windows;
using Rboxlo.Core.Common;

namespace Rboxlo.Launcher
{
    /// <summary>
    /// Interaction logic for App.xaml
    /// </summary>
    public partial class App : Application
    {
        private void AppStartup(object sender, StartupEventArgs e)
        {
            // open launcher with our arguments
            InternetConnection connection = new InternetConnection();
            UI.MainWindow window = new UI.MainWindow(e.Args, connection);
            window.Show();
        }
    }
}
