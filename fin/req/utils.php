<?
require("req/mysql.php");

class cls_utils extends cls_mysql
{
var $mail_boundary="----_=_NextPart_000_01c1.94f.653432c1";
var $mail_boundary2="----_=_NextPart_001_01c1.94f.653432c1";
var $mail_priority=3;
var $mail_from;
var $mail_to;
var $mail_subj;
var $mail_body_plain;
var $mail_body_html;
var $mail_body;
var $attach;
var $attach_type;
var $html_error;

	function err_to_str($num)
	{
		$err[1]="Ошибка управления или попытка взлома системы. Админу отправлено сообщение";
		$err[2]="Ошибка авторизации!!!";
		$err[11]="Ошибка выполнения SQL-запроса!!!";
		$err[21]="Вы не задали название раздела.";
		$err[22]="Длинна слова превышает допустимые 50 символов!";
		$err[23]="Такой раздел уже существует!";
		$err[24]="Такого раздела в базе нет!";
		$err[25]="Вы не задали название тексту";
		$err[26]="Длинна названия текста привышает допустимые 200 символов";
		$err[27]="Текст слишком короткий";
		$err[28]="Текст слишком длинный";
		//$err[29]="";
		//$err[30]="";
$err[31]="Не могу открыть файл для записи:";
//".$this->PATH_DATA."/".$this->in_text_id.";
		$err[32]="Невозможно удалить файл:";
		//".$this->PATH_DATA."/".$this->in_text_id.";
		//$err[]="";
		//$err[]="";
		//$err[]="";
		//$err[]="";
		//$err[]="";
		//$err[]="";
		//$err[]="";
		//$err[]="";
		$err[101]="Ошибка выполнения запроса.";
		if($this->DEBUG_LEVEL>=$num) $this->mail_to_noc($err[$num]);
		return($err[$num]);
	}
	
	function err_to_html($num)
	{
	$text="<b>ERROR:</b><font colot=#FF0000>"."$this->err_to_str($num)"."</font>";
	$text.="<p>Раскажите админу <a href=mailto:cub4lt@ukr.net>cub4lt@ukr.net</a>";
	
	$text.="<br><br><div align=right>Super_Mega_Giga_Master</div><br><br>";
					return $text;
	}
	
	function ok_to_html($text)
	{
 		return "<font color=green>$text</font><br><br>";
	}


	function mailer($from,$to,$subj,$body)
	{
$from="From:$from\nReply-To:$from\nX-Priority:1\nContent-Type:text/plain;charset=\"koi8-u\"\nContent-Transfer-Enoding:8bit";
		$from=convert_cyr_string($from,"w","k");
		$to=convert_cyr_string($to,"w","k");
		$subj=convert_cyr_string($subj,"w","k");
		$body=convert_cyr_string($body,"w","k");
		mail($to,$subj,$body,$from);
	}
	
	function mail_to_noc($message)
	{
		global $REQUEST_URI;
		for($i=0;$i<count($EMAIL_NOC);$i++)
		{
//$this->mailer("Mail_Robot",this->EMAIL_NOC[$i],"Fatal ERROR!!!","Error:$message\nDateTime:"".$this->today_date()."".$this->today_time()."\n".Remote IP:".$this->remote_ip()."\n\nURI:$REQUEST_URI\n\n$SQL_QUERY:".$this->sql_query."\nSQL_ERROR:".$this->sql_err);
			usleep(100000);
		}
	}
	
	function today_date()
	{
		$ret=date("D d M Y",date("d-m-Y"));
		return($ret);
	}
	
	function today_time()
	{
		
				$ret=date("G:i:s",time());
				return($ret);
		
	}
	
	function remote_ip()
	{
		global $REMOTE_ADDR;
		return($REMOTE_ADDR);
	}

	function mail_header()
	{
		//$header="Reply-To:".$this->mail_from."\n";
		//$header.="MIME-Version:1.0\n";
		//$header.="Content-Type:multipart/mixed; boundary=\"".$this->mail_boundary."\"\n";
		//$header.="X-Prioriti:".$this->mail_prioriti."\n"
		return($header);
	}	
	
	function mail_body($html,$plain)
	{
		$this->mail_body_html=$html;
		$this->mail_body_plain=$plain;
	}
	
	function mail_attach($name,$type,$data)
	{
		$this->mail_attach_type[$name]=$type;
		$this->mail_attach[$name]=$date;
	}
	
	function mail_failattach($path,$type)
	{
		$name=ereg_replace("/(.+/)/","",$path);
		if(!$r=fopen($path,'r')) return(1);
		$this->mail_attach($name,$type,fread($r,filesize($path)));
		fclose($r);
		return(0);
	}
	
	function mail_body_create()
	{
		$this->mail_body="\n\n";
		$this->mail_body.=$this->body_plain;
		if(strlen($this->body_html)>0)
		{
			$this->mail_body.="--".$this->boundary."\n";
			$this->mail_body.="Content-Type: 	multipart/alternative;boundary=".$this->mail_boundary2."\n\n";
			$this->mail_body.=$this->mail_body_plan."\n";
			$this->mail_body.="--".$this->mail_boundary2."\n";
			$this->mail_body.="Content-Type: text/plain\n";
			$this->mail_body.="Content-Transfer-Encoding:quoted-printable\n\n";
			$this->mail_body.=$this->body_plain."\n\n";
			$this->mail_body.="--".$this->boundary2."\n";
			$this->mail_body.="Content-Type: text/html\n";
			$this->mail_body.="Content-Transfer-Encoding:quoted-printable\n\n";
			$this->mail_body.=$this->mail_body_html."\n\n";
			$this->mail_body.="--$boundary2--\n";
		}
		else
		{
			$this->mail_body.="--".$this->boundary."\n";
			$this->mail_body.="Contant-Type: text/plain\n";
			$this->mail_body.="Content-Transfer-Encoding: quoted-printable\n\n";
			$this->mail_body.=$this->body_plain."\n\n--";
			$this->mail_body.=$this->boundary."\n";
		}
		reset($this->attach_type);
		while(list($name,$content_type)=each($this->attach_type))
		{
			$this->mail_body.="\n--".$this->boundary."\n";
			$this->mail_body.="Content-Type: $content_type\n";
			$this->mail_body.="Content-Transfer-Encoding: base64\n";
			$this->mail_body.="Content-Disposition: attachment;";
			$this->mail_body.="filename=\"$name\"n\n";
			$this->mail_body.=chunk_split(base64_encode($this->atcmnt[$name]))."\n";
		}
		$this->mail_body.="--".$this->boundary."--\n";
		return(0);
	}
	
	function html_headers()
	{
		header("Cache-Control:max-age=".$this->CACHE_TIME.",must-revalidate");
		header("Last-Modified:".gmdate("D,d M Y H:i:s",time()-3600)."GMT");
		header("Expires:".gmdate("D,d M Y H:i:s", time()+$this->CACHE_TIME)."GMT");
		header("Content-type:text/html");
		header("content-Type: image/gif");
	}
	
}
?>