for(;;) {
 try {
    Write-Output "Starting Server"
	node server.js
 }
 catch {
 
 }

 # wait for a minute
 Start-Sleep 1
}
