<?php
namespace root\core\Router;

class Router
{
    private $controllerReflectionInstance,
            $controllerInstance,
            $controllerName,
            $controllerAddress,
            $registry;

    /**
     * Using this constructor method we inject the Object of the Registry class and then calling other methods of the class!
     * @param \root\core\Registry\Registry $registry Object of the class Registry.
     */
    public function __construct(\root\core\Registry\Registry $registry)
    {
        $this->registry = $registry;

        $this->getController();
        $this->instantiatingController();
        $this->checkingMethod();
        $this->callingMethod();

        return;
    }

    /**
     * This method make the name and address of the controller ready and check to see if there is any controller like what is called and then setting them to their proper properties.
     * In addition that, if the called controller does not exist, user will be redirected to the error controller in it's Controller nofFound methid (action) with the oarameter that it's value is 'Controller'.
     * @return void
     */
    private function getController()
    {
        $this->controllerAddress = FILE_PATH . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->registry->request->getController() . 'Controller.php';
        
        if(file_exists($this->controllerAddress) && is_readable($this->controllerAddress))
        {
            $this->controllerName = $this->registry->request->getController() . 'Controller';
            return;
        }

        header("Location: " . URL . 'error/notFound/Controller/');
        exit;
    }

    /**
     * At this part (method) we instantiate the called controller using the Reflection class and saving that object in it's propper property.
     * @return void
     */
    private function instantiatingController()
    {
        require_once $this->controllerAddress;
        $this->controllerReflectionInstance = new \ReflectionClass($this->controllerName);
        $this->controllerInstance = $this->controllerReflectionInstance->newInstance($this->registry->getInstance());

        return;
    }

    /**
     * This method checks to see if there is requested method (action) and it is public, otherwise user will be redirected to the controller of error and it's method of notFound with the parameter of the "Method" (the value of parameter).
     * @return void
     */
    private function checkingMethod()
    {
        if(
            !($this->controllerReflectionInstance->hasMethod($this->registry->request->getMethod())) ||
            !($this->controllerReflectionInstance->getMethod($this->registry->request->getMethod())->isPublic()))
        {
            header("Location: " . URL . 'error/notFound/Method/');
            exit;
        }

        return;
    }

    /**
     * Now, we are ready to invoke the requested method (action) and also pass the arguments to that method.
     * @return void
     */
    private function callingMethod()
    {
        $this->controllerReflectionInstance->getMethod($this->registry->request->getMethod())->invoke($this->controllerInstance, $this->registry->request->getArgs());
        return;
    }
}