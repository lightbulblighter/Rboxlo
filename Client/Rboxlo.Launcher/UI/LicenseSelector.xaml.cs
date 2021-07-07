using System;
using System.Windows;
using System.IO;

using Rboxlo.Launcher.Base;
using Microsoft.Win32;

namespace Rboxlo.Launcher.UI
{
    /// <summary>
    /// Interaction logic for LicenseSelector.xaml
    /// 
    /// A fancy file select form that gives vague instructions...
    /// </summary>
    public partial class LicenseSelector : Window
    {
        public LicenseSelector()
        {
            InitializeComponent();
        }

        private void WindowLoaded(object sender, RoutedEventArgs e)
        {
            // set our messages
            this.Title = Translation.FetchMessage("oops", false);
            SelectButton.Content = Translation.FetchMessage("select", false);
            Message1.Content = Translation.FetchMessage("license_message1");
            Message2.Content = Translation.FetchMessage("license_message2",  false);
            
            // remove close button and icon
            WindowModifier.RemoveCloseButton(this);
            WindowModifier.RemoveIcon(this);
        }

        private void SelectButtonClick(object sender, RoutedEventArgs e)
        {
            bool exists = false;
            string location = null;

            while (!exists || location == null)
            {
                OpenFileDialog dialog = new OpenFileDialog();
                dialog.InitialDirectory = "shell:Downloads"; // most probable place where it'll be
                dialog.Filter = "Rboxlo License (*.bin)|*.bin|All files(*.*)";
                dialog.CheckFileExists = true;

                if (dialog.ShowDialog() == true)
                {
                    location = dialog.FileName;
                    exists = File.Exists(location);
                }
            }

            File.Copy(location, Path.Combine(LauncherConstants.ApplicationFolder, "license.bin"));
            this.Close();
        }
    }
}
