<?php
    header("Content-Type: text/plain");
?>

--HTTPService Stress Test/Uncapping Maximum HTTPService Requests Test
local HttpService = game:GetService("HttpService")
HttpService.HttpEnabled = true

-- e.g, <ns1:jobID>StressTest-time()</ns1:jobID><ns1:script>start(60)</ns1:script>
function start(amount)
for i = 0, amount do
	e = HttpService:GetAsync("http://blank.org/")
	print("sent request #".. i .. " and first 6 letters are ".. e:sub(1, 6))
end

print(i, "requests sent")
end

-- EOF