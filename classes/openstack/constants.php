<?php
namespace mod_bigbluebuttonbn\openstack;


class constants {
    // Heat required outputs names
    const BBB_HOST = "bbb_url";
    const BBB_SHARED_KEY = "bbb_shared_key";

    // DB BBB host flags
    const BBB_HOST_READY = "Ready";
    const BBB_HOST_FAILED = "Failed";
    const BBB_HOST_IN_PROGRESS = "In Progress";
    const BBB_HOST_DELETED = "Deleted";
    const BBB_HOST_DELETE_EEROR = "Error at delete";

    // Minutes before be considered upcoming meeting
    const UPCOMING_MEETINGS_MINUTES = 30;

    // Max minutes to build the BBB Host
    const DEFAULT_TIMEOUT_MINUTES = 14;
}