<?php

return [

    /**
     * The minimum time (in seconds) that must have passed before
     * submitting a form, or the speedtrap will be triggered
     */
    'threshold' => 5,

    /**
     * Here the input name can be configured, the name will be
     * generated from the Application Name if not configured
     * Recommended to leave as null to auto generate it
     */
    'input-name' => null,

    /**
     * Here the component name can be changed to avoid conflicts
     * if you already have a component with the same name
     */
    'component-name' => 'speedtrap',

];
