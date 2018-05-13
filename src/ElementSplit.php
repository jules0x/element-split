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
    private static $icon = 'font-icon-block-banner';

    private static $table_name = 'ElementSplit';

    private static $singular_name = 'Split element';

    private static $description = 'Image and HTML text element';

    private static $db = [
        'HTML' => 'HTMLText',
        'Align' => 'Enum("Left, Right", "Right")',
        'Prefer' => 'Enum("Neither, Image, Content")',
        'MinHeight' => 'Int'
    ];

    private static $has_one = [
        'Image' => Image::class
    ];

    private static $owns = ['Image'];

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            $fields
                ->fieldByName('Root.Main.HTML')
                ->setTitle(_t(__CLASS__ . '.ContentLabel', 'Content'));
        });

        $fields = parent::getCMSFields();

        $fields->removeByName([
            'TitleAndDisplayed'
        ]);

        $imageUpload = UploadField::create('Image', 'Featured Image');
        $imageUpload->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png']);

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

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title'),
            $imageUpload,
            $align,
            $prefer,
        ], 'HTML');

        $fields->addFieldsToTab('Root.Settings', [
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
