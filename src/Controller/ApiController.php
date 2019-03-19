<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\CustomerApiKeys;
use App\Repository\CustomerApiKeysRepository;

class ApiController
{

    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;


    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respond($success=0, $data=[], $errors=[], $headers = [])
    {
        return new JsonResponse(['success'=>$success,'data'=>$data,'errors'=>$errors]);
        //return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $errors
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data = ['success'=>0,
            'errors' => [$errors],
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }



  /**
  * Returns a 422 Unprocessable Entity
  *
  * @param string $message
  *
  * @return Symfony\Component\HttpFoundation\JsonResponse
  */
  public function respondValidationError($message = 'Validation errors')
  {
    return $this->setStatusCode(422)->respondWithErrors($message);
  }

  /**
   * Returns a 404 Not Found
   *
   * @param string $message
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   */
  public function respondNotFound($message = 'Not found!')
  {
      return $this->setStatusCode(404)->respondWithErrors($message);
  }

  /**
   * Returns a 201 Created
   *
   * @param array $data
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   */
  public function respondCreated($success=0,$data = [],$errors=[])
  {
      return $this->setStatusCode(201)->respond($success,$data,$errors);
  }

  // this method allows us to accept JSON payloads in POST requests
  // since Symfony 4 doesn't handle that automatically:

  protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
  {
      $data = json_decode($request->getContent(), true);

      if (json_last_error() !== JSON_ERROR_NONE) {
          return null;
      }

      if ($data === null) {
          return $request;
      }

      $request->request->replace($data);

      return $request;
  }


  public function auth(\App\Repository\CustomerApiKeysRepository $customerApiKeysRepository)
  {
      if(!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION'])!=37 || preg_match('/^[a-f0-9]{37}$/',$_SERVER['HTTP_AUTHORIZATION'])!=1)
      {
          return -1;
      }

      try {
        // get api key
        $user_id = $customerApiKeysRepository->getCustomerApiKey($_SERVER['HTTP_AUTHORIZATION']);
      } catch (\Exception $e) {
          return -1;
      }

      return $user_id;
  }
  

}
