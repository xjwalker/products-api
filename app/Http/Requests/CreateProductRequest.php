<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationRuleException;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * CreateProductRequest constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct();
        $this->productRepository = $productRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'bail|required|filled|string|max:120',
            'price' => 'bail|numeric',
        ];
    }

    public function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function ($validator) {
            if ($validator->errors()->count()) {
                return;
            }

            $data = $validator->getData();
            $product = $this->productRepository->getByTitle($data['title']);
            if (!is_null($product)) {
                throw new ValidationRuleException('title', 'unique');
            }

            if (is_null($data['price']) || !isset($data['price']) || empty($data['price'])) {
                throw new ValidationRuleException('price', 'required');
            }

            if ($data['price'] < 0) {
                throw new ValidationRuleException('price', 'invalid_price_value');
            }
        });

        return $validator;
    }
}
