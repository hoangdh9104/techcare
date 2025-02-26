<?php

/** Set Sidebar item active */

function setActive(array $route)
{
    if (is_array($route)) {
        foreach ($route as $r) {
            if (request()->routeIs($r)) {
                // request()->routeIs($r) là một phương thức của Laravel,
                // kiểm tra xem route hiện tại của request có khớp với tên route được truyền vào $r không.
                return 'active';
            }
        }
    }
}
