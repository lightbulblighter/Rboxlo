--rbxsig2%itMggmlrO0TGIanOALTyys6uJMWxLJJMnvAq1uNViuKNEZYpMCI1AKpakOlU9CnSb/a9SXtctu10p9s+w8Ov5yjHZCPh1NPKYS4VVsSjoKxFK3IrsZJsrEwEjE6qZ+yaE2+BXgb7qVWtzRXRtEEVnGlyaXPNvIdh35d1UNm8D7/Gon4JdtUcrQy+rLpHMvea0CuhxNwfl2jeILxDUbVabzQFIB8rkTNvJyE+gpoGRyGuLEg2YBeEuEnjedt9fdW0Ofd/X1r6tJpZTJKbwddmnNzVlHhBizUvEGCifMRK5YSJt+Au0/DB9xW2Wb8GhB13aNLAWxw9NpgVp5mo87egyA==%
-- Loaded by StartGameSharedScript --
pcall(function() game:SetCreatorID(0, Enum.CreatorType.User) end)

pcall(function() game:GetService("SocialService"):SetFriendUrl("http://ab.ozzt.pw/Game/LuaWebService/HandleSocialRequest.ashx?method=IsFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetBestFriendUrl("http://ab.ozzt.pw/Game/LuaWebService/HandleSocialRequest.ashx?method=IsBestFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupUrl("http://ab.ozzt.pw/Game/LuaWebService/HandleSocialRequest.ashx?method=IsInGroup&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRankUrl("http://ab.ozzt.pw/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRank&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRoleUrl("http://ab.ozzt.pw/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRole&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("GamePassService"):SetPlayerHasPassUrl("http://ab.ozzt.pw/Game/GamePass/GamePassHandler.ashx?Action=HasPass&UserID=%d&PassID=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetProductInfoUrl("https://ab.ozzt.pw/marketplace/productinfo?assetId=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetDevProductInfoUrl("https://ab.ozzt.pw/marketplace/productDetails?productId=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetPlayerOwnsAssetUrl("https://ab.ozzt.pw/ownership/hasasset?userId=%d&assetId=%d") end)
pcall(function() game:SetPlaceVersion(0) end)
pcall(function() game:SetVIPServerOwnerId(0) end)

local result = pcall(function() game:GetService("ScriptContext"):AddStarterScript(1) end)
if not result then
  pcall(function() game:GetService("ScriptContext"):AddCoreScript(1,game:GetService("ScriptContext"),"StarterScript") end)
end