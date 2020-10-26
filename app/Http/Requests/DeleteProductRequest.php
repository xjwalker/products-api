<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationRuleException;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DeleteProductRequest extends FormRequest
{

    /** @var ProductRepository */
    private $productRepository;

    /**
     * DeleteProductRequest constructor.
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
            'id' => 'bail|required|numeric|filled'
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

            if ($data['id'] < 0) {
                throw new ValidationRuleException('id', 'invalid_price_value');
            }

            $product = $this->productRepository->getById($data['id']);
            if (is_null($product)) {
                throw new ValidationRuleException('id', 'exists');
            }

            $this->request->add(['product' => $product]);
        });

        return $validator;
    }
}
