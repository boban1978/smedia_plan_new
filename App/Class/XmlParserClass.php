<?php

class XMLParserClass
{
    var $parser;
    var $xml;
    var $document;
    var $stack;
    function XMLParserClass($xml = '')
    {
        $this->xml = $xml;
        $this->stack = array();
    }
    function Parse()
    {


        $this->parser = xml_parser_create();


        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, 'StartElement', 'EndElement');
        xml_set_character_data_handler($this->parser, 'CharacterData');

        if (!xml_parse($this->parser, $this->xml))
            $this->HandleError(xml_get_error_code($this->parser), xml_get_current_line_number($this->parser), xml_get_current_column_number($this->parser));

        xml_parser_free($this->parser);
    }
    function HandleError($code, $line, $col)
    {
        trigger_error('XML Parsing Error at '.$line.':'.$col.'. Error '.$code.': '.xml_error_string($code));
    }
    function GenerateXML()
    {
        return $this->document->GetXML();
    }
    function GetStackLocation()
    {
        $return = '';

        foreach($this->stack as $stack)
            $return .= $stack.'->';
        
        return rtrim($return, '->');
    }
    function StartElement($parser, $name, $attrs = array())
    {
        $name = strtolower($name);

        if (count($this->stack) == 0) 
        {
            $this->document = new XmlTagClass($name, $attrs);

            $this->stack = array('document');
        }
        else
        {
            $parent = $this->GetStackLocation();

            eval('$this->'.$parent.'->AddChild($name, $attrs, '.count($this->stack).');');

            eval('$this->stack[] = $name.\'[\'.(count($this->'.$parent.'->'.$name.') - 1).\']\';');
        }
    }
    function EndElement($parser, $name)
    {
        array_pop($this->stack);
    }
    function CharacterData($parser, $data)
    {
        $tag = $this->GetStackLocation();

        eval('$this->'.$tag.'->tagData .= trim($data);');
    }
}



?>