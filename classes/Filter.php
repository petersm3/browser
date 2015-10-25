<?php
class Filter {
    public function parse($post) {
        $getFilters='';
        if(isset($post['filters'])) {
            foreach ($post['filters'] as $key => $value) {
                $getFilters.='filter[]=' . $value . '&';
            }
        }
        if(isset($post['offset'])) {
            $getFilters.='offset=' . $post['offset'] . '&';
        }
        // Do not need trailing &
        return substr($getFilters, 0, -1);
    }
/* vim:set noexpandtab tabstop=4 sw=4: */
}
