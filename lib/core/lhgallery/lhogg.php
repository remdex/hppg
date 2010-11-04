<?php 
/*  Ogg (vorbis/theora) php manipulation library  v1.3g
by Nicolas Ricquemaque <f1iqf@hotmail.com>
GNU GPL >= 2.1  http://www.gnu.org/copyleft/lesser.html GNU LGPL
Primary for use with itheora  http://menguy.aymeric.free.fr/theora/   */

class erLhcoreClassOgg
{
    // Public variables
    var $LastError=false; 	// false if no error occured, else error text
    var $Streams=array();	// All the file data stored here

    // Private variables
    var $Data;
    var $cache,$cachedir; 	// shall we use & write cache and where ?
    var $tagvendor; 		// change it to false if you don't want the oggclass to add its name to your vendor field when writting new comments
    var $StreamsUTF8; 		// if true, the vendor and comment information will be in utf8 inside the Streams array. Else, will be ansi.
    var $InputFile,$OutputFile,$MaxTime=0,$refresh;

    // constructor; $options contains a conbination of (CACHING or NOCACHING) + (ANSI or UTF8) + NOVENDORTAG + UPDATECACHE
    // by default $options enables CACHING, ANSI strings and Vendor Tag tagging
    // UPDATECACHE is forcing the analysing of the file and the update of the cache, if caching enabled.
    // $cachedir is optional cache directory
    function erLhcoreClassOgg ($OggFile)
    {
        // Options
        $this->StreamsUTF8=0;     
        $this->tagvendor=false;
        $this->Streams['oggfile']=$OggFile;

        //if file could not be read from cache (or sizes did not match), we parse it from file
        $this->analyze();
    }

    // Public methods
    function GetPicture ($framepos=1,$where=false) //return $path of picture or false; $frame=frame number; $where=path to file to create, if false will be in cache;
    {
        if (!$this->Streams['picturable'])
        {
            if (!$this->Picturable()) return(false);
            else $this->Streams['picturable']=true;
        }
        if (isset($this->Streams['theora']['pictures']))
        {
            if (isset($this->Streams['theora']['pictures'][$framepos]))
            {
                if (file_exists($this->Streams['theora']['pictures'][$framepos])) return ($this->Streams['theora']['pictures'][$framepos]);
            }
        }
        
        $file=$where;
        
        $errorreport=error_reporting(0);
        $movie = new ffmpeg_movie($this->Streams['oggfile'],false);
        if (!$movie) { error_reporting($errorreport); return($this->creturn("Unable to extract a picture from ".$this->Streams['oggfile'])); }
        $frame=$movie->getFrame($framepos);
        if (!$frame) { error_reporting($errorreport); return($this->creturn("Unable to extract the frame #".$framepos." from ".$this->Streams['oggfile'])); }
        $image=$frame->toGDImage();
        if (!$image) { error_reporting($errorreport); return($this->creturn("Unable to extract an image in frame #".$framepos." from ".$this->Streams['oggfile'])); }
        if (!imagejpeg($image,$file)) { error_reporting($errorreport); return($this->creturn("Unable to save a jpeg image from frame #".$framepos." from ".$this->Streams['oggfile'])); }
        if (!isset($this->Streams['theora']['pictures'])) $this->Streams['theora']['pictures']=array();
        $this->Streams['theora']['pictures'][$framepos]=$file;
        
        error_reporting($errorreport);
        $this->creturn(true);
        return($file);
    }

    // Private methods
    function creturn($error=false)	{ if (is_string($error)) { $this->LastError="OGG Error: $error"; return(false); } else { $this->LastError=false; return($error); } }
    function Decode($string)
    {
        if ($this->StreamsUTF8) return((utf8_encode(utf8_decode($string)) == $string)?$string:utf8_encode($string)); // if string utf8, return it, or encode it in utf8
        else return((utf8_encode(utf8_decode($string)) == $string)?utf8_decode($string):$string); // return ansi
    }
    function EncodeUTF8($string) 	{ return((utf8_encode(utf8_decode($string)) == $string)?$string:utf8_encode($string)); }
    function Read32LE(&$buffer,$pos) { return(ord($buffer[$pos+0])+(ord($buffer[$pos+1])<<8)+(ord($buffer[$pos+2])<<16)+(ord($buffer[$pos+3])<<24)); } // Read 32 bits little endian from buffer from index $pos
    function readBits ($nb,&$buffer,&$bitptr) //read and decodes an integer up to 32 bits from the buffer
    {
        if ($nb>32) $nb=32;
        if ($nb==0) return(0);
        for ($bit=$nb,$r=0;$bit>0;$bit--,$bitptr++) $r+=(ord(substr($buffer,$bitptr>>3,1))&pow(2,7-$bitptr%8))>0?pow(2,$bit-1):0;
        return ($r);
    }
  
    function Picturable() //boolean, says if this server/library is able to extract a picture from movie
    {
        if (!isset($this->Streams['theora'])) return($this->creturn("Only for video streams..."));
        if ((strpos($this->Streams['oggfile'],"://")!==false)) return ($this->creturn("Picture extraction not possible on a remote server ! "));
        $extensions=get_loaded_extensions();
        if  (!in_array("ffmpeg",$extensions)) return($this->creturn("Module ffmpeg not found on this PHP server !"));
        if ($gd=in_array("gd",$extensions))
        {
            $GDArray=gd_info();
            if (intval(ereg_replace('[[:alpha:][:space:]()]+', '', $GDArray['GD Version'])) < 2) $gd=false;
        }
        if (!gd) return($this->creturn("Module gd2 not found on this PHP server !"));
        return($this->creturn(true));
    }
  
    function analyze() // Parse headers to retrieve identification and comments infos
    {

        unset($this->Streams['vorbis']); unset($this->Streams['theora']);
        unset($this->Streams['tmpfile']); unset($this->Streams['tmpfileptr']);
        unset($this->Streams['size']); unset($this->Streams['duration']);

        $this->Streams['source']="file";
        $this->Streams['encoding']=$this->StreamsUTF8?"utf-8":"ansi";
        $this->Data="";

        if (strpos($this->Data,"OggS")===false) // not a remote file or could cold read inside it
        {
            $inputfile = @fopen($this->Streams['oggfile'], "rb");
            if ($s=@filesize($this->Streams['oggfile'])) $this->Streams['size']=$s;
            if (!is_resource($inputfile)) return ($this->creturn("Could not open OGG file ".$this->Streams['oggfile']));
            // First read the first 65536 bytes of the file to parse identification and comments headers
            if (! ($this->Data = fread($inputfile, 0xFFFF))) return ($this->creturn("Could not read in OGG file ".$this->Streams['oggfile']));
        }

        $this->Streams['summary']=basename($this->Streams['oggfile'])." (".floor($this->Streams['size']/1024)." kB)\n\n";
        for ($pos=0;($pos=strpos($this->Data,"OggS",$pos))!==false;$pos++) // parse OGG pages to retrieve interresting data
        {
            if (ord($this->Data[$pos+4])!=0) continue; // unknown stream structure version. We continue parsing, but don't analyse this one which might be a sync problem
            $packet=$pos+27+ord($this->Data[$pos+26]); // offset of the packet after header
            if (isset($this->Streams['cmml']) && $this->Read32LE($this->Data,$pos+14)==$this->Streams['cmml']['serial']) // read CMML data
            {
                $nextogg=strpos($this->Data,"OggS",$pos+1);
                if (isset($this->Streams['cmml']['text'])) $this->Streams['cmml']['text'].="\n";
                $this->Streams['cmml']['text'].=substr($this->Data,$packet,$nextogg-$packet);
            }
            if ($this->Read32LE($this->Data,$pos+18)==0) // Page Count = 0 : we can find the stream type and read identification headers
            {
                if ((substr($this->Data,$packet+1,6)=="vorbis") && !isset($this->Streams['vorbis'])) // decode vorbis identification header
                {
                    $this->Streams['vorbis']=array();
                    $vorbis=&$this->Streams['vorbis'];
                    $vorbis['serial']=$this->Read32LE($this->Data,$pos+14);
                    if (ord($this->Data[$packet]) != 0x01) return($this->creturn(sprintf("Incorrect Vorbis Identification Header(0x%x)",ord($this->Data[$packet]))));
                    if ($this->Read32LE($this->Data,$packet+7)!=0) return($this->creturn("Incorrect Vorbis stream version = ".$this->Read32LE($this->Data,$packet+7)));
                    if (($vorbis['channels'] = ord($this->Data[$packet+11])) == 0) return($this->creturn("Incorrect Vorbis channels number = ".ord($this->Data[$packet+11])));
                    if (($vorbis['samplerate']= $this->Read32LE($this->Data,$packet+12)) == 0) return($this->creturn("Incorrect Vorbis sample rate = ".$this->Read32LE($this->Data,$packet+12)));
                    $vorbis['maxbitrate'] = $this->Read32LE($this->Data,$packet+16);
                    $vorbis['nombitrate'] = $this->Read32LE($this->Data,$packet+20);
                    $vorbis['minbitrate'] = $this->Read32LE($this->Data,$packet+24);
                    $vorbis['bitrate']=($vorbis['nombitrate'] != 0)?$vorbis['nombitrate']:($vorbis['minbitrate'] + $vorbis['maxbitrate']) / 2;
                }
                elseif ((substr($this->Data,$packet+1,6)=="theora") && !isset($this->Streams['theora'])) // decode theora identification header
                {
                    $this->Streams['theora']=array();
                    $theora=&$this->Streams['theora'];
                    $theora['serial']=$this->Read32LE($this->Data,$pos+14);
                    if (ord($this->Data[$packet]) != 0x80) return($this->creturn(sprintf("Incorrect Theora Identification Header(0x%x)",ord($this->Data[$packet]))));
                    if ((($theora['vmaj']=ord($this->Data[$packet+7]))!=3) || (($theora['vmin']=ord($this->Data[$packet+8]))!=2)) return($this->creturn("Incorrect Theora stream version"));
                    $bitptr=($packet+9)*8;
                    $theora['vrev']=$this->readBits(8,$this->Data,$bitptr);
                    $theora['fmbw']=$this->readBits(16,$this->Data,$bitptr); $theora['fmbh']=$this->readBits(16,$this->Data,$bitptr);
                    $theora['picw']=$this->readBits(24,$this->Data,$bitptr); $theora['pich']=$this->readBits(24,$this->Data,$bitptr);
                    $theora['picx']=$this->readBits(8,$this->Data,$bitptr); $theora['picy']=$this->readBits(8,$this->Data,$bitptr);
                    $theora['width']=$theora['picw']; $theora['height']=$theora['pich'];
                    $theora['frn']=$this->readBits(32,$this->Data,$bitptr); $theora['frd']=$this->readBits(32,$this->Data,$bitptr); $theora['frate']=round($theora['frn']/$theora['frd'],2);
                    $theora['parn']=$this->readBits(24,$this->Data,$bitptr); $theora['pard']=$this->readBits(24,$this->Data,$bitptr); if ($theora['parn']*$theora['pard']!=0) $theora['pixelaspectratio']=$theora['parn'].":".$theora['pard']; else $theora['pixelaspectratio']="1:1";
                    $theora['cs']=$this->readBits(8,$this->Data,$bitptr); if ($theora['cs']==1) $theora['colorspace']="Rec. 470M"; elseif ($theora['cs']==2) $theora['colorspace']="Rec. 470BG";
                    $theora['nombr']=$this->readBits(24,$this->Data,$bitptr);
                    $theora['qual']=$this->readBits(6,$this->Data,$bitptr);
                    $theora['kfgshift']=$this->readBits(5,$this->Data,$bitptr);
                    $theora['pf']=$this->readBits(2,$this->Data,$bitptr); if ($theora['pf']==0) $theora['pixelformat']="4:2:0"; elseif ($theora['pf']==2) $theora['pixelformat']="4:2:2"; elseif ($theora['pf']==3) $theora['pixelformat']="4:4:4";
                }
                elseif ((substr($this->Data,$packet,8)=="fishead\0")&& !isset($this->Streams['skeleton'])) //decode ogg skeleton primary header
                {
                    $this->Streams['skeleton']=array();
                    $this->Streams['skeleton']['version']=(ord($this->Data[$packet+8])+(ord($this->Data[$packet+9])<<8)).".".(ord($this->Data[$packet+10])+(ord($this->Data[$packet+11])<<8));
                    if (ord($this->Data[$packet+44])>0) $this->Streams['skeleton']['utc']=substr($this->Data,$packet+44,20);
                }
                elseif ((substr($this->Data,$packet,8)=="CMML\0\0\0\0")&& !isset($this->Streams['cmml'])) //decode ogg CMML primary header
                {
                    $this->Streams['cmml']=array();
                    $this->Streams['cmml']['serial']=$this->Read32LE($this->Data,$pos+14);
                    $this->Streams['cmml']['version']=(ord($this->Data[$packet+8])+(ord($this->Data[$packet+9])<<8)).".".(ord($this->Data[$packet+10])+(ord($this->Data[$packet+11])<<8));
                }
            }
            elseif ($this->Read32LE($this->Data,$pos+18)==1) // Page Count = 1 : we can read comments headers
            {
                $type=substr($this->Data,$packet+1,6);
                if ($type=="vorbis")
                {
                    if (ord($this->Data[$packet])!=0x03) return($this->creturn(sprintf("Incorrect Vorbis Comment Header(0x%x)",ord($this->Data[$packet]))));
                    $table=&$this->Streams['vorbis'];
                }
                elseif ($type=="theora")
                {
                    if (ord($this->Data[$packet])!=0x81) return($this->creturn(sprintf("Incorrect Theora Comment Header(0x%x)",ord($this->Data[$packet]))));
                    $table=&$this->Streams['theora'];
                }
                else continue; // unknown page 1, neither theora nor vorbis comments
                $offset=$packet;
                $lenv=$this->Read32LE($this->Data,$offset+7);
                $table['vendor']=$this->Decode(substr($this->Data,$offset+11,$lenv));
                $offset+=11+$lenv;
                $ncomments=$this->Read32LE($this->Data,$offset);
                $offset+=4;
                $table['comments']=array();
                for ($i=0; $i<$ncomments; $i++)
                {
                    $lcomment=$this->Read32LE($this->Data,$offset);
                    $table['comments'][$i]=$this->Decode(substr($this->Data,$offset+4,$lcomment));
                    $offset+=4+$lcomment;
                }
                if ($type=="vorbis") $offset++; //vorbis format adds a "0x01" at the end, which theora doesn't
                $table['commentlen']=$offset-$packet;
                // This last part only to get necessary information to change the comments tags
                $table['commentpos']=$pos;	//page position in file
                $table['commentnext']=strpos($this->Data,"OggS",$pos+1); //next page position in file
                $lseg=ord($this->Data[$pos+26])-($table['commentlen']>>8)-1;
                $table['commentleftSegments']=substr($this->Data,$pos+27+($table['commentlen']>>8)+1,$lseg);
            }
            if (isset($this->Streams['vorbis']['vendor'])&&isset($this->Streams['theora']['vendor'])&&!isset($this->Streams['cmml'])) break; // we already have what we need, we can stop parsing
        }

        if (isset($this->Streams['skeleton'])) // read ogg skeleton secondary headers
        {
            for ($pos=0;($pos=strpos($this->Data,"fisbone\0",$pos))!==false;$pos++) // parse fisbone pages to retrieve interresting data
            {
                $serial=$this->Read32LE($this->Data,$pos+12);
                $nextfis=strpos($this->Data,"fisbone\0",$pos+1);
                $nextogg=strpos($this->Data,"OggS",$pos+1);
                $endfis=($nextfis>0 && $nextfis<$nextogg)?$nextfis:$nextogg;
                $posmessage=$pos+8+$this->Read32LE($this->Data,$pos+8);
                $lenmessage=$endfis-$posmessage;
                $message=trim(substr($this->Data,$posmessage,$lenmessage));
                if (strlen($message)>0)
                {
                    if (isset($this->Streams['theora']) && $this->Streams['theora']['serial']==$serial) $this->Streams['theora']['skeleton']=$message;
                    if (isset($this->Streams['vorbis']) && $this->Streams['vorbis']['serial']==$serial) $this->Streams['vorbis']['skeleton']=$message;
                }
            }
        }

        // Then read the last 65536 bytes of the file to get last granular pos to calculate streams duration
        $endbuffer="";
        if (isset($this->Streams['size'])&&($this->Streams['size']>0xFFFF))
        {
            if (is_resource($inputfile)) // still open: so this is local file
            {
                @fseek($inputfile,-1*0xFFFF,SEEK_END);
                if (! ($endbuffer = fread($inputfile, 0xFFFF))) return ($this->creturn("Could not read in OGG file ".$this->Streams['oggfile']));
                fclose($inputfile);
            }
            else {
                $url = parse_url($this->Streams['oggfile']);
                $port = isset($url['port']) ? $url['port'] : 80;
                $inputfile = @fsockopen($url['host'], $port,$errno,$errstr,5);
                if ($inputfile)
                {
                    stream_set_timeout($inputfile, 2);
                    $query=isset($url['query'])?"?".$url['query']:"";
                    @fwrite($inputfile, "GET ".$url['path'].$query." HTTP/1.1\r\nHost: ".$url['host']."\r\nUser-Agent:".OGGCLASSVERSION."\r\nAccept-Ranges: bytes\r\nRange: bytes=".($this->Streams['size']-0xFFFF)."-\r\nConnection: close\r\n\r\n");
                    for ($i=0;$i<70;$i++) if (!feof($inputfile)) $endbuffer.=@fread($inputfile, 1024);	else break;
                    fclose($inputfile);
                }
            }
        }
        else $endbuffer=&$this->Data;
        for ($pos=0;($pos=strpos($endbuffer,"OggS",$pos))!==false;$pos++) // parse OGG pages to retrieve interesting data
        {
            if (isset($this->Streams['vorbis'])&&($this->Read32LE($endbuffer,$pos+6)!=-1)&&($this->Read32LE($endbuffer,$pos+14)==$this->Streams['vorbis']['serial'])&&(ord($endbuffer[$pos+5])&0x4))
            $this->Streams['vorbis']['duration']=round($this->Read32LE($endbuffer,$pos+6) / $this->Streams['vorbis']['samplerate']);

            elseif (isset($this->Streams['theora'])&&($this->Read32LE($endbuffer,$pos+6)!=-1)&&($this->Read32LE($endbuffer,$pos+14)==$this->Streams['theora']['serial'])&&(ord($endbuffer[$pos+5])&0x4))
            {
                $this->Streams['theora']['framecount']=($this->Read32LE($endbuffer,$pos+6)>>$this->Streams['theora']['kfgshift']) + ($this->Read32LE($endbuffer,$pos+6)&(pow(2,$this->Streams['theora']['kfgshift'])-1));
                $this->Streams['theora']['duration']=round( $this->Streams['theora']['framecount'] * $this->Streams['theora']['frd'] / $this->Streams['theora']['frn']);
            }
            if (isset($this->Streams['vorbis']['duration'])&&isset($this->Streams['theora']['duration'])) break; // we alread have what we need, we can stop parsing
        }

        // update summary
        if (isset($this->Streams['theora']))
        {
            $this->Streams['summary'].="Video (theora): ";
            if (isset($this->Streams['theora']['duration'])) $this->Streams['summary'].=$this->Streams['theora']['duration']."s ";
            $this->Streams['summary'].=$this->Streams['theora']['width']."x".$this->Streams['theora']['height'];
            $this->Streams['summary'].=" ".$this->Streams['theora']['frate']."fps";
            if ($q=$this->Streams['theora']['qual']) $this->Streams['summary'].=" Q=$q";
            $this->Streams['summary'].="\n";
            if (isset($this->Streams['theora']['comments'])) foreach ($this->Streams['theora']['comments'] as $value) $this->Streams['summary'].="$value\n";
            $this->Streams['summary'].="\n";
        }
        if (isset($this->Streams['vorbis']))
        {
            $this->Streams['summary'].="Audio (Vorbis";
            $this->Streams['summary'].=" ".floor($this->Streams['vorbis']['bitrate']/1000)."kb/s";
            $this->Streams['summary'].="): ";
            if (isset($this->Streams['vorbis']['duration'])) $this->Streams['summary'].=$this->Streams['vorbis']['duration']."s ";
            $this->Streams['summary'].=($this->Streams['vorbis']['channels']>1)?"stereo":"mono";
            $this->Streams['summary'].=" ".floor($this->Streams['vorbis']['samplerate']/1000)."kB/s\n";
            if (isset($this->Streams['vorbis']['comments'])) foreach ($this->Streams['vorbis']['comments'] as $value) $this->Streams['summary'].="$value\n";
        }

        //global duration is the biggest of each
        if (isset($this->Streams['vorbis']['duration'])&&isset($this->Streams['theora']['duration'])) $this->Streams['duration']=$this->Streams['vorbis']['duration']>$this->Streams['theora']['duration']?$this->Streams['vorbis']['duration']:$this->Streams['theora']['duration'];
        elseif (isset($this->Streams['vorbis']['duration'])) $this->Streams['duration']=$this->Streams['vorbis']['duration'];
        elseif (isset($this->Streams['theora']['duration'])) $this->Streams['duration']=$this->Streams['theora']['duration'];

        $this->Streams['picturable']=$this->Picturable(); // Can the server extract a picture ?

        return($this->creturn(true));
    }
}
?>