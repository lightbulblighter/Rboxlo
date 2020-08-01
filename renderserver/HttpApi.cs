using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Net;
using Newtonsoft.Json.Linq;

namespace RboxloRenderserver
{
    public static class HttpApi
    {
        /*
                            __------__
                          /~          ~\
                         |    //^\\//^\|
                       /~~\  ||  o| |o|:~\
                      | |6   ||___|_|_||:|
                       \__.  /      o  \/'
                        |   (       O   )
               /~~~~\    `\  \         /
              | |~~\ |     )  ~------~`\
             /' |  | |   /     ____ /~~~)\
            (_/'   | | |     /'    |    ( |
                   | | |     \    /   __)/ \
                   \  \ \      \/    /' \   `\
                     \  \|\        /   | |\___|
                       \ |  \____/     | |
                       /^~>  \        _/ <
                      |  |         \       \
                      |  | \        \        \
                      -^-\  \       |        )
                           `\_______/^\______/
        */


        private static WebClient InternetConnection = new WebClient();

        public static JObject GetQueue(string authorization)
        {


            return null;
        }
    }
}
