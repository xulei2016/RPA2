<?php
namespace App\Services\Flow;

/**
 * FlowInterface interface
 *
 * @Description
 * @author Hsu Lay
 * @since
 */
interface FlowInterface{

    /**
     * pass function
     *
     * @param [type] $node_id
     * @return void
     * @Description
     * @author Hsu Lay
     */
	public function pass($node_id);


    /**
     * unpass function
     *
     * @param [type] $record_id
     * @return void
     * @Description
     * @author Hsu Lay
     */
    public function unpass($record_id);
    
    
}