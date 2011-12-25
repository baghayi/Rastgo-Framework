<?php

namespace root\core\interfaces\iLibraryController;

interface iLibraryController {
    const libraryNamespace = 'root\library\*\index\*';

    public function call($libraryName, $constructorsArgument = array());

    public function libraryExistence($libraryName);

    public function classAddress($libraryName);

    public function methodExistence($libraryName, $methodName);
}