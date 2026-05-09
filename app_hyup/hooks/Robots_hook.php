<?php
defined('BASEPATH') or exit('No direct script access allowed');

function block_search_engine_indexing()
{
    if (!headers_sent()) {
        header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet, noimageindex', true);
    }
}
