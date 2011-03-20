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
     * The minimum and maximum latitude and longitude values for the CONUS (continental United States) grid
     * The four corners are:
     *     20.191999, -121.554001
     *     20.331773,  -69.208160
     *     50.105547,  -60.885558
     *     49.939721, -130.103438
     * from: http://www.weather.gov/forecasts/xml/SOAP_server/ndfdXMLclient.php?whichClient=CornerPoints&sector=conus
     */
    /**
     * @var float Minimum latitude
     */
    static $minLatitude  =   20.19;
    /**
     * @var float Maximum latitude
     */
    static $maxLatitude  =   50.11;
    /**
     * @var float Minimum longitude
     */
    static $minLongitude = -130.11;
    /**
     * @var float Maximum longitude
     */
    static $maxLongitude =  -60.87;


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


    /**
     * The hour intervals that will be averaged for morning/afternoon/evening/night weather organization
     */
    /**
     * @var array The hours that will be averaged for the morning interval
     */
    static $morningInterval   = array('08', '11');
    /**
     * @var array The hours that will be averaged for the afternoon interval
     */
    static $afternoonInterval = array('11', '14', '17');
    /**
     * @var array The hours that will be averaged for the evening interval
     */
    static $eveningInterval   = array('17', '20', '23');
    /**
     * @var array The hours that will be averaged for the night interval
     */
    static $nightInterval     = array('23', '02', '05');
    /**
     * @var array The hours that will be averaged for the full day interval
     */
    static $fullDayInterval   = array('08', '11', '14', '17', '20', '23');
}
?>
