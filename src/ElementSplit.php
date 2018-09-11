<?php

namespace Jules0x\Elements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TextField;

class ElementSplit extends BaseElement
{
    private static $icon = 'font-icon-columns';

    private static $table_name = 'ElementSplit';

    private static $singular_name = 'Split element';

    private static $description = 'Image and HTML text side-by-side';

    private static $db = [
        'Content' => 'HTMLText',
        'Align' => 'Enum("Left, Right", "Right")',
        'Prefer' => 'Enum("Neither, Image, Content")',
        'MinHeight' => 'Int'
    ];

    private static $has_one = [
        'Image' => Image::class
    ];

    private static $owns = [
        'Image'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $this->addContentFields($fields);
        $this->addSettingsFields($fields);

        return $fields;
    }

    private function addContentFields($fields)
    {
        $imageUpload = UploadField::create('Image');
        $imageUpload->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png']);

        $fields->addFieldsToTab('Root.Main', [
            $imageUpload
        ], 'Content');

        return $fields;
    }

    private function addSettingsFields($fields)
    {
        $align = OptionsetField::create(
            'Align',
            'Image placement',
            singleton('Jules0x\Elements\ElementSplit')->dbObject('Align')->enumValues(),
            'Right'
        );

        $prefer = OptionsetField::create(
            'Prefer',
            'Give extra width to...',
            singleton('Jules0x\Elements\ElementSplit')->dbObject('Prefer')->enumValues(),
            'Neither'
        );

        $fields->addFieldsToTab('Root.Settings', [
            $align,
            $prefer,
            NumericField::create('MinHeight', 'Minimum height')->setRightTitle('px')
        ]);

        return $fields;
    }

    public function getIsLeftAligned()
    {
        if ($this->Align === 'Left') {
            return true;
        }
    }

    public function getType()
    {
        return $this->config()->get('singular_name');
    }
}
