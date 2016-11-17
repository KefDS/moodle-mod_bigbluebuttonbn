<?php
/**
 * Big Blue Button stack.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

namespace mod_bigbluebuttonbn\openstack;

require_once dirname(dirname(dirname(__FILE__))) . "/vendor/autoload.php";
use OpenCloud\Orchestration\Resource\Stack;

class bbb_stack extends Stack {
    // CONSTANTS
    const BBB_HOST = "bbb_url";
    const BBB_SHARED_KEY = "bbb_shared_key";

    private $stack;

    public function __construct(Stack $stack) {
        $this->stack = $stack;
    }

    public function get_bbb_host_data() {
        if(preg_match("/COMPLETE/", $this->stack->status)) {
            return $this->get_bbb_host_outputs();
        }
        elseif(preg_match("/PROGRESS/", $this->stack->status)) {
            return null;
        }

        // Other state is an error (for now)
        $error_messaage = "Error in BBB server creation. Stack status: " .
            $this->stack->status;
        throw new \Exception($error_messaage);
    }

    private function get_bbb_host_outputs() {
        $required_keys = [self::BBB_HOST, self::BBB_SHARED_KEY];
        $required_outputs = [];

        foreach($required_keys as $key) {
            foreach($this->stack->outputs as $output) {
                if($output->output_key == $key) $required_outputs[$key] = $output->output_value;
            }
            // Output does not exist
            if(!array_key_exists($key, $required_outputs)) {
                $error_message = "Error in Heat template. The template requires the following key to work: " . $key;
                throw new \Exception($error_message);
            }
        }

        return $required_outputs;
    }
}
