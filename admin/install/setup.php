<?php

class ShcmsSetup {
    
    /**
     * Минимальная версии PHP
     * 
     * @var string
     */
    const minPhpVersion	= '5.3.0';
	
    /** 
     * Минимальная версии MySQL
     *
     * @var string
     */
    const minDb_mysql = '4.1.0';
	
    /**
     * Минимальная версии PHP
     *
     * @var string
     */
    const prefPhpVersion = '5.3.3';

    /**
     * Минимальная версии MySQL
     *
     * @var string
     */
    const prefDb_mysql = '5.0.0';
	
    /**
     * Кодировка
     *
     * @var string
     */
    const charSet = 'utf-8';

    /**
     * Сохраненные данные
     *
     * @access	private
     * @var	array
     */
    protected $template		= '';
    protected $_html        = '';
    
    private static $_savedData	= array();

    public function php() {
        return static::minPhpVersion;
    }
    
	public function template()
	{
		return $this->template;
	}
	public function addContent( $content, $prepend=false )
	{
		if( $prepend )
		{
			return $this->_html = $content . $this->_html;
		}
		else
		{
			return $this->_html .= $content;
		}
	}        
    
    public function doExecute() {
	$filesOK       = NULL;
	$extensions    = get_loaded_extensions();
	$extensionsOK  = TRUE;
	$extensionData = array();
		
	/* Test Extensions */
	$INSTALLDATA = array();
	include(H.'admin/install/setup/required.php');/*noLibHook*/
    
		if ( is_array( $INSTALLDATA ) && count( $INSTALLDATA ) )
		{
			foreach( $INSTALLDATA as $data )
			{
				if ( ! in_array( $data['testfor'], $extensions ) )
				{
					if( $data['nohault'] )
					{
						$data['_ok']	= 1;		// Anything but true or false
						$extensionsOK	= 1;		// Anything but true or false
					}
					else
					{
						$extensionsOK = FALSE;
					}
				}
				else
				{
					$data['_ok'] = TRUE;
				}
				
				$extensionData[] = $data;
			}
		}
        
               return $extensionData;
    }
}
