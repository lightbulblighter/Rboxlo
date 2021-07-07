using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using DiscordRPC;
using DiscordRPC.Message;

namespace Rboxlo.Launcher.Discord
{
    public class RPC
    {
        private static string clientID;
        private static DiscordRpcClient client;

        public RPC(string _clientID)
        {
            clientID = _clientID;

            client = new DiscordRpcClient(clientID);
            
        }

        public static void SetVisiblity(bool visibility)
        {

        }

        public static void Destroy()
        {

        }

        public static void SetCurrentPlace(string title, string client)
        {

        }

        public static void CreateParty(int players, int maxPlayers)
        {

        }

        public static void UpdatePlayers(int newPlayers)
        {

        }
    }
}
