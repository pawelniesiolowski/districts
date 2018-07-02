<?php

namespace Districts\Service;


use Districts\Model\DataContext;
use Districts\Model\DistrictCollection;
use Districts\Model\DomainObjectCollectionInterface;

class DistrictDataParser implements DataParserInterface
{
    private $districtFactory;

    public function __construct(DomainObjectFactoryInterface $districtFactory)
    {
        $this->districtFactory = $districtFactory;
    }

    /**
     * @param DataContext $dataContext
     * @throws \Exception
     * @return DomainObjectCollectionInterface
     */
    public function parseData(DataContext $dataContext): DomainObjectCollectionInterface
    {
        $districtCollection = new DistrictCollection();

        for ($i = $dataContext->min; $i <= $dataContext->max; $i++) {
            $path = sprintf($dataContext->formattedPath, $i);

            $response = file_get_contents($path);
            $response = trim(strip_tags($response));

            $matches = [];

            preg_match($dataContext->regExp, $response,$matches);

            $matches[1] = trim($matches[1]);

            $matches[2] = trim($matches[2]);
            $matches[2] = str_replace(',', '.', $matches[2]);
            $matches[2] = (float)$matches[2];

            $matches[3] = (int)trim($matches[3]);

            $matches[0] = null;
            $matches[4] = $dataContext->city;

            $data = array_combine($dataContext->arrayKeys, $matches);

            $district = $this->districtFactory->createDomainObject($data);
            $districtCollection->add($district);
        }

        return $districtCollection;
    }
}