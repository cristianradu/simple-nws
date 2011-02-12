<?php
namespace SimpleNWS;
/**
 * Configuration class for the SimpleNWS library
 *
 * @author Cristian Radu <code@cristianradu.com>
 * @version 1.0
 * @package SimpleNWS
 */
class Configuration
{
    /**
     * @var string constant The URL for the National Weather Service interface
     */
    const C_NWS_URL = 'http://www.weather.gov/forecasts/xml/sample_products/browser_interface/ndfdXMLclient.php?';

    /**
     * @var array The allowed values for the timeframe parameter
     */
    static $allowedTimeframeValues = array('now', 'today', 'week');

    /**
     * @var string The product type being returned. This is always 'time-series'
     */
    static $productType = 'product=time-series';

    /**
     * @var array The parameters to be submitted upon request
     */
    static $requestParameters = array('maxt',  // Maximum Temperature
                                      'mint',  // Minimum Temperature
                                      'temp',  // Temperature
                                      'appt',  // Apparent Temperature
                                      'wx',    // Weather
                                      'qpf',   // Liquid Precipitation Amount
                                      'snow',  // Snowfall Amount
                                      'sky',   // Cloud Cover Amount
                                      'rh');   // Relative Humidity

}
?>
