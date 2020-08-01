// RboxloGameserver.cpp : This file contains the 'main' function. Program execution begins and ends there.
//

#include <iostream>
#include <stdlib.h>
#include <crtdbg.h>
#include <tchar.h>
#include <atlbase.h>
#include <atlstr.h>
#include <atlcom.h>
#include <atlutil.h>

#include <string>

class CServiceHandle
{
    SC_HANDLE handle;
public:
    CServiceHandle(SC_HANDLE handle) :handle(handle) {}
    ~CServiceHandle() { CloseServiceHandle(handle); }
    operator SC_HANDLE() const { return handle; }
};

void InstallService()
{
    TCHAR szPath[MAX_PATH];

    if (!GetModuleFileName(NULL, szPath, MAX_PATH))
        throw std::runtime_error("GetModuleFileName failed");

    // Get a handle to the SCM database. 

    CServiceHandle schSCManager = OpenSCManager(
        NULL,                    // local computer
        NULL,                    // ServicesActive database 
        SC_MANAGER_CREATE_SERVICE);

    if (NULL == schSCManager)
        throw std::runtime_error("OpenSCManager failed");

    // Create the service

    CServiceHandle schService = CreateService(
        schSCManager,              // SCM database 
        L"RboxloGameServer",       // name of service 
        L"Rboxlo Game Server",     // service name to display 
        SERVICE_ALL_ACCESS,        // desired access 
        SERVICE_WIN32_OWN_PROCESS, // service type 
        SERVICE_AUTO_START,        // start type 
        SERVICE_ERROR_NORMAL,      // error control type 
        szPath,                    // path to service's binary 
        NULL,                      // no load ordering group 
        NULL,                      // no tag identifier 
        NULL,                      // no dependencies 
        NULL,                      // LocalSystem account 
        NULL);                     // no password 

    if (schService == NULL)
    {
        if (GetLastError() == ERROR_SERVICE_EXISTS)
        {
            printf("Service already installed\n");
            return;
        }
        throw std::runtime_error("CreateService failed");
    }
    else
        printf("Service installed successfully\n");

    SERVICE_FAILURE_ACTIONS fa;
    fa.dwResetPeriod = 100;
    fa.lpRebootMsg = NULL;
    fa.lpCommand = NULL;
    fa.cActions = 3;
    SC_ACTION sa[3];
    sa[0].Delay = 5000;
    sa[0].Type = SC_ACTION_RESTART;
    sa[1].Delay = 55000;
    sa[1].Type = SC_ACTION_RESTART;
    sa[2].Delay = 60000;
    sa[2].Type = SC_ACTION_RESTART;
    fa.lpsaActions = sa;

    if (!ChangeServiceConfig2(schService, SERVICE_CONFIG_FAILURE_ACTIONS, &fa))
        throw std::runtime_error("ChangeServiceConfig2 failed");
}
int main()
{
    std::cout << "Hello World!\n";
}

// Run program: Ctrl + F5 or Debug > Start Without Debugging menu
// Debug program: F5 or Debug > Start Debugging menu

// Tips for Getting Started: 
//   1. Use the Solution Explorer window to add/manage files
//   2. Use the Team Explorer window to connect to source control
//   3. Use the Output window to see build output and other messages
//   4. Use the Error List window to view errors
//   5. Go to Project > Add New Item to create new code files, or Project > Add Existing Item to add existing code files to the project
//   6. In the future, to open this project again, go to File > Open > Project and select the .sln file
