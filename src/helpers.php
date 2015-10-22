<?php

if (!function_exists('accentless')) {
    /**
     * Accentless given string
     *
     * @param string $text
     *
     * @return string
     */
    function accentless($text)
    {
        $search  = explode(',', 'á,â,ã,ä,å,ā,ă,ą,Á,Â,Ã,Ä,Å,Ā,Ă,Ą,é,è,é,é,ê,ë,ē,ĕ,ė,ę,ě,É,Ē,Ĕ,Ė,Ę,Ě,í,ì,í,î,ï,ì,ĩ,ī,ĭ,Ì,Í,Î,Ï,Ì,Ĩ,Ī,Ĭ,ő,ó,ô,õ,ö,ō,ŏ,ő,Ò,Ó,Ô,Õ,Ö,Ō,Ŏ,Ő,ű,ù,ú,û,ü,ũ,ū,ŭ,ů,Ű,Ù,Ú,Û,Ü,Ũ,Ū,Ŭ,Ů');
        $replace = explode(',', 'a,a,a,a,a,a,a,a,A,A,A,A,A,A,A,A,e,e,e,e,e,e,e,e,e,e,e,E,E,E,E,E,E,i,i,i,i,i,i,i,i,i,I,I,I,I,I,I,I,I,o,o,o,o,o,o,o,o,O,O,O,O,O,O,O,O,u,u,u,u,u,u,u,u,u,U,U,U,U,U,U,U,U,U');

        return str_replace($search, $replace, $text);
    }
}