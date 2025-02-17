<?php

namespace Solspace\Calendar\Library\ColorJizz\Formats;

use Solspace\Calendar\Library\ColorJizz\ColorJizz;

/**
 * XYZ represents the XYZ color format.
 *
 * @author Mikee Franklin <mikeefranklin@gmail.com>
 */
class XYZ extends ColorJizz
{
    /**
     * The x.
     */
    public ?float $x = null;

    /**
     * The y.
     */
    public ?float $y = null;

    /**
     * The z.
     */
    public ?float $z = null;

    /**
     * Create a new XYZ color.
     */
    public function __construct(float $x, float $y, float $z)
    {
        $this->toSelf = 'toXYZ';
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    /**
     * A string representation of this color in the current format.
     *
     * @return string The color in format: $x,$y,$z
     */
    public function __toString(): string
    {
        return \sprintf('%s,%s,%s', $this->x, $this->y, $this->z);
    }

    /**
     * Convert the color to Hex format.
     *
     * @return Hex the color in Hex format
     */
    public function toHex(): Hex
    {
        return $this->toRGB()->toHex();
    }

    /**
     * Convert the color to RGB format.
     *
     * @return RGB the color in RGB format
     */
    public function toRGB(): RGB
    {
        $var_X = $this->x / 100;
        $var_Y = $this->y / 100;
        $var_Z = $this->z / 100;

        $var_R = $var_X * 3.2406 + $var_Y * -1.5372 + $var_Z * -0.4986;
        $var_G = $var_X * -0.9689 + $var_Y * 1.8758 + $var_Z * 0.0415;
        $var_B = $var_X * 0.0557 + $var_Y * -0.2040 + $var_Z * 1.0570;

        if ($var_R > 0.0031308) {
            $var_R = 1.055 * $var_R ** (1 / 2.4) - 0.055;
        } else {
            $var_R *= 12.92;
        }
        if ($var_G > 0.0031308) {
            $var_G = 1.055 * $var_G ** (1 / 2.4) - 0.055;
        } else {
            $var_G *= 12.92;
        }
        if ($var_B > 0.0031308) {
            $var_B = 1.055 * $var_B ** (1 / 2.4) - 0.055;
        } else {
            $var_B *= 12.92;
        }
        $var_R = max(0, min(255, $var_R * 255));
        $var_G = max(0, min(255, $var_G * 255));
        $var_B = max(0, min(255, $var_B * 255));

        return new RGB($var_R, $var_G, $var_B);
    }

    /**
     * Convert the color to XYZ format.
     *
     * @return XYZ the color in XYZ format
     */
    public function toXYZ(): self
    {
        return $this;
    }

    /**
     * Convert the color to Yxy format.
     *
     * @return Yxy the color in Yxy format
     */
    public function toYxy(): Yxy
    {
        $Y = $this->y;
        $x = $this->x / ($this->x + $this->y + $this->z);
        $y = $this->y / ($this->x + $Y + $this->z);

        return new Yxy($Y, $x, $y);
    }

    /**
     * Convert the color to HSV format.
     *
     * @return HSV the color in HSV format
     */
    public function toHSV(): HSV
    {
        return $this->toRGB()->toHSV();
    }

    /**
     * Convert the color to CMY format.
     *
     * @return CMY the color in CMY format
     */
    public function toCMY(): CMY
    {
        return $this->toRGB()->toCMY();
    }

    /**
     * Convert the color to CMYK format.
     *
     * @return CMYK the color in CMYK format
     */
    public function toCMYK(): CMYK
    {
        return $this->toCMY()->toCMYK();
    }

    /**
     * Convert the color to CIELab format.
     *
     * @return CIELab the color in CIELab format
     */
    public function toCIELab(): CIELab
    {
        $Xn = 95.047;
        $Yn = 100.000;
        $Zn = 108.883;

        $x = $this->x / $Xn;
        $y = $this->y / $Yn;
        $z = $this->z / $Zn;

        if ($x > 0.008856) {
            $x = $x ** (1 / 3);
        } else {
            $x = (7.787 * $x) + (16 / 116);
        }
        if ($y > 0.008856) {
            $y = $y ** (1 / 3);
        } else {
            $y = (7.787 * $y) + (16 / 116);
        }
        if ($z > 0.008856) {
            $z = $z ** (1 / 3);
        } else {
            $z = (7.787 * $z) + (16 / 116);
        }
        if ($y > 0.008856) {
            $l = (116 * $y) - 16;
        } else {
            $l = 903.3 * $y;
        }
        $a = 500 * ($x - $y);
        $b = 200 * ($y - $z);

        return new CIELab($l, $a, $b);
    }

    /**
     * Convert the color to CIELCh format.
     *
     * @return CIELCh the color in CIELCh format
     */
    public function toCIELCh(): CIELCh
    {
        return $this->toCIELab()->toCIELCh();
    }
}
