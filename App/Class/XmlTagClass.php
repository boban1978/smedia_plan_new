<?php


class XmlTagClass
{
    var $tagAttrs;
    var $tagName;
    var $tagData;
    var $tagChildren;
    var $tagParents;
    function XmlTagClass($name, $attrs = array(), $parents = 0)
    {
        $this->tagAttrs = array_change_key_case($attrs, CASE_LOWER);

        $this->tagName = strtolower($name);

        $this->tagParents = $parents;

        $this->tagChildren = array();
        $this->tagData = '';
    }
    function AddChild($name, $attrs, $parents)
    {    
        if(!isset($this->$name))
            $this->$name = array();

        if(!is_array($this->$name))
        {
            trigger_error('You have used a reserved name as the name of an XML tag. Please consult the documentation (http://www.weather2umbrella.com) and rename the tag named '.$name.' to something other than a reserved name.', E_USER_ERROR);

            return;
        }

        $child = new XmlTagClass($name, $attrs, $parents);

        $this->{$name}[] =& $child;

        $this->tagChildren[] =& $child;
    }
    function GetXML()
    {
        $out = "\n".str_repeat("\t", $this->tagParents).'<'.$this->tagName;

        foreach($this->tagAttrs as $attr => $value)
            $out .= ' '.$attr.'="'.$value.'"';

        if(empty($this->tagChildren) && empty($this->tagData))
            $out .= " />";
        else
        {    
            if(!empty($this->tagChildren))        
            {
                $out .= '>';
                foreach($this->tagChildren as $child)
                    $out .= $child->GetXML();
                $out .= "\n".str_repeat("\t", $this->tagParents);
            }
            elseif(!empty($this->tagData))
                $out .= '>'.$this->tagData;  
            $out .= '</'.$this->tagName.'>';
        }
        return $out;
    }
}


?>