<?php
class Filter {
    public function parse($post) {
        $getFilters='';
        foreach ($post['filters'] as $key => $value) {
            $getFilters.='filter[]=' . $value . '&';
        }
        // Do not need trailing &
        return substr($getFilters, 0, -1);
    }
/* vim:set noexpandtab tabstop=4 sw=4: */
}
