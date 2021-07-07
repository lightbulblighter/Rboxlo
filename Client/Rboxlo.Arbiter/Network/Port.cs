using System;
using System.Threading;
using System.Net;
using System.Net.NetworkInformation;

namespace Rboxlo.Arbiter.Network
{
    public static class Port
    {
        /// <summary>
        /// Check if startPort is available, incrementing and
        /// checking again if it's in use until a free port is found
        /// </summary>
        /// <param name="startPort">The first port to check</param>
        /// <param name="type">Type of port to check</param>
        /// <returns>The first available port</returns>
        public static int FindNextAvailablePort(int startPort, TransportType type)
        {
            int port = startPort;
            bool isAvailable = true;

            var mutex = new Mutex(false, string.Concat("Global/", Properties.Settings.Default.PortReleaseGuid));
            mutex.WaitOne();

            try
            {
                IPGlobalProperties ipGlobalProperties = IPGlobalProperties.GetIPGlobalProperties();
                IPEndPoint[] endPoints = null;

                if (type == TransportType.Tcp)
                {
                    endPoints = ipGlobalProperties.GetActiveTcpListeners();
                }
                else if (type == TransportType.Udp)
                {
                    endPoints = ipGlobalProperties.GetActiveUdpListeners();
                }
                else
                {
                    throw new NotImplementedException();
                }

                while (!isAvailable && port < IPEndPoint.MaxPort)
                {
                    if (!isAvailable)
                    {
                        port++;
                        isAvailable = true;
                    }

                    foreach (IPEndPoint endPoint in endPoints)
                    {
                        if (endPoint.Port != port) continue;
                        isAvailable = false;
                        break;
                    }
                }

                if (!isAvailable)
                {
                    throw new Exception("NoAvailablePortsInRangeException");
                }

                return port;
            }
            finally
            {
                mutex.ReleaseMutex();
            }
        }
    }
}
