<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationRuleException;
use App\Modules\Product\ProductRepository\ProductRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

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
            //
        ];
    }

    public function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->count()) {
                return;
            }

            $data = $validator->getData();

            if (!isset($data['id']) || empty($data['id'])) {
                throw new ValidationRuleException('id', 'required');
            }
            if ($data['id'] <= 0) {
                throw new ValidationRuleException('id', 'integer');
            }
            $product = $this->productRepository->getById($data['id']);
            if (is_null($product)) {
                throw new ValidationRuleException('id', 'exists');
            }

            if (isset($data['price']) && $data['price'] < 0) {
                throw new ValidationRuleException('price', 'invalid_price_value');
            }
            if (isset($data['title']) && strlen($data['title']) > 120) {
                throw new ValidationRuleException('title', 'max');
            }

            $this->request->add(['product' => $product]);
        });

        return $validator;
    }
}
