<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * number_member_entries Plugin
 *
 * @category    Plugin
 * @author      WEB Secret
 * @link        http://websecret.by
 */

$plugin_info = array(
    'pi_name'       => 'Number members entries',
    'pi_version'    => '1.0',
    'pi_author'     => 'WEB Secret',
    'pi_author_url' => 'http://websecret.by',
    'pi_description'=> 'Return number of members entries from channels',
    'pi_usage'      => Number_member_entries::usage()
);


class Number_member_entries {

    public $return_data;

    // --------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->EE =& get_instance();
    }

    // --------------------------------------------------------------------

    public function count()
    {
        $channels  = $this->EE->TMPL->fetch_param('channels');
        $statuses  = $this->EE->TMPL->fetch_param('status', 'open');
        $member_id = $this->EE->TMPL->fetch_param('member_id', $this->EE->session->userdata('member_id') );

        // build sql
        $this->EE->db->select('entry_id')
                        ->from('channel_titles');

        if( count( $this->param_to_array($channels) ) > 0 )
            $this->EE->db->where_in('channel_id', $this->param_to_array($channels) );

        if( count( $this->param_to_array($statuses) ) > 0 )
            $this->EE->db->where_in('status', $this->param_to_array($statuses) );

        if( count( $this->param_to_array($member_id) ) > 0 )
            $this->EE->db->where_in('author_id', $this->param_to_array($member_id) );

        // result
        $q = $this->EE->db->get();
        $total_entries = $q->num_rows();

        return $total_entries;
    }

    /**
     * Convert tag params to array
     */
    private function param_to_array($str)
    {
        $array = ($str && $str != '') ? explode('|', $str) : array();
        return $array;
    }

    /**
     * Plugin Usage
     */
    public static function usage()
    {
        ob_start();
?>
Number members entries
Return number of members entries from channels

{exp:number_member_entries:count status="open|closed" member_id="1" channels="1|2"}
If status is empty - search only in entries with open status
If channels is empty - search in all channels
If member_id is empty - search in current member entries
<?php
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
}


/* End of file pi.number_member_entries.php */
/* Location: /system/expressionengine/third_party/number_member_entries/pi.number_member_entries.php */
