<?php 
/**
 * THIS SOFTWARE IS PROVIDED "AS IS" AND ANY EXPRESSED OR IMPLIED WARRANTIES, INCLUDING,
 * BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
 * PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING
 * IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright (c) 2003 amfphp.org
 * @package flashservices
 * @subpackage io
 */
/**
 * AMFInputStream class is a wrapper to extend the functionality of of the raw input stream.
 * 
 * The input stream object takes the raw data and reads it in linearly by n number of bytes where
 * n is the number of bytes dictated by the datatype the corresponding method requires.  For example
 * the readByte accessor grabs a single byte from the stream and advances the current cursor where the
 * read double method gets the next 8 bytes from the stream and advances the cursor.
 * 
 * @package flashservices
 * @subpackage io
 * @version $Id: CharsetHandler.php,v 1.5 2005/07/05 07:40:53 pmineault Exp $
 */
 
class CharsetHandler
{
	function CharsetHandler($mode)
	{
		$this->_method = CharsetHandler::getMethod();
		$this->_phpCharset = CharsetHandler::getPhpCharset();
		$this->_sqlCharset = CharsetHandler::getSqlCharset();
		$this->_mode = $mode;
		
		$newset = "";
		if($this->_mode == "flashtophp")
		{
			$this->_fromCharset = "utf-8";
			$this->_toCharset = $this->_phpCharset;
			$newset = $this->_phpCharset;
		}
		else if($this->_mode == "phptoflash")
		{
			$this->_fromCharset = $this->_phpCharset;
			$this->_toCharset = "utf-8";
			$newset = $this->_phpCharset;
		}
		else if($this->_mode == "sqltophp")
		{
			$this->_fromCharset = $this->_sqlCharset;
			$this->_toCharset = $this->_phpCharset;
			$newset = $this->_sqlCharset;
		}
		else if($this->_mode == "sqltoflash")
		{
			$this->_fromCharset = $this->_sqlCharset;
			$this->_toCharset = "utf-8";
			$newset = $this->_sqlCharset;
		}
		
		//Don't try to reencode charsets for nothing
		if($this->_fromCharset == $this->_toCharset)
		{
			$this->_method = "none";
		}
	}
	
	function transliterate($string)
	{
		switch($this->_method)
		{
			case "none" :
				return $string;
				break;
			case "iconv":
				return iconv($this->_fromCharset,$this->_toCharset, $string);
				break;
			case "utf8_decode":
				return ($this->_mode == "flashtophp" ? utf8_decode($string) : utf8_encode($string));
				break;
			case "mbstring":
				return mb_convert_encoding($string, $this->_toCharset, $this->_fromCharset);
				break;
			case "recode":
				return recode_string($this->_fromCharset . ".." . $this->_toCharset, $string);
				break;
			default:
				return $string;
				break;
		}
	}
	
	/**
	 * Sets the charset handling method
	 *
	 * @param string $location One of "none", "iconv", "mbstring", "recode"
	 */
	function getMethod($val=NULL)
	{
		static $method = 0;
		if($val != NULL)
		{
			if($val == 'utf8_encode')
			{
				$val = 'utf8_decode';
			}
			$method = $val;
		}
		return $method;
	}
	
	function setMethod($val=0){
		return CharsetHandler::getMethod($val);
	}
	
	function getPhpCharset($val=NULL)
	{
		static $phpCharset = 0;
		if($val != NULL)
		{
			$phpCharset = strtolower($val);
		}
		return $phpCharset;
	}
	
	function setPhpCharset($val=0){
		return CharsetHandler::getPhpCharset($val);
	}
	
	function getSqlCharset($val=NULL)
	{
		static $sqlCharset = 0;
		if($val != NULL)
		{
			$sqlCharset = strtolower($val);
		}
		return $sqlCharset;
	}
	
	function setSqlCharset($val=0){
		return CharsetHandler::getSqlCharset($val);
	}
}
?>