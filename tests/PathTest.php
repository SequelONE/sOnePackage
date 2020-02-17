<?php
use sequelone\sOnePackage\sOnePackage;
global $modx;
$sonepackage = new sOnePackage($modx);

/**
 * Tests the sOnePackage service class, in particular the path placeholder features.
 */
class PathTest extends PHPUnit_Framework_TestCase {
    /** @var sOnePackage $sOnePackage */
    public $sonepackage;
    public $package;

    public function setUp() {
        global $sonepackage;
        $this->sonepackage = $sonepackage;
    }

    public function testIsProperObject() {
        $this->assertInstanceOf('sequelone\sOnePackage\sOnePackage', $this->sonepackage);
    }

    /**
     * Tests "static" path placeholders, the ones that are hardcoded to be available based on user and settings.
     *
     * @dataProvider providerParsePathVariables
     *
     * @param $path
     * @param $expected
     */
    public function testParsePathVariables($path, $expected) {
        $this->assertEquals($expected, $this->sonepackage->parsePathVariables($path));
    }

    /**
     * @return array
     */
    public function providerParsePathVariables() {
        // Gotta set variables on the global because the Provider is set up before this class
        /** @var sOnePackage $sOnePackage */
        global $sonepackage;
        $sonepackage->modx->getUser()->set('id', 15);
        $sonepackage->modx->getUser()->set('username', 'my_user_name');
        $sonepackage->modx->setOption('assets_url', 'assets/');
        $sonepackage->modx->setOption('site_url', 'http://localhost/');

        $year = date('Y');
        $month = date('m');
        $day = date('d');
        return array(
            array(
                "assets/[[+year]]/[[+month]]/[[+day]]/",
                "assets/$year/$month/$day/"
            ),
            array(
                'assets/uploads/',
                'assets/uploads/'
            ),
            array(
                'assets/uploads/[[+foo]]',
                'assets/uploads/'
            ),
            array(
                'assets/[[+foo]]/uploads/',
                'assets/uploads/'
            ),
            array(
                "assets/[[+username]]/",
                "assets/my_user_name/"
            ),
            array(
                "assets/[[+user]]_[[+username]]/",
                "assets/15_my_user_name/"
            ),
            array(
                "[[++assets_url]][[+year]]/[[+month]]/[[+date]]/",
                "assets/$year/$month/$day/"
            ),
            array(
                "[[++site_url]]uploads/",
                "http://localhost/uploads/"
            ),
        );
    }

    public function testSetPathVariables () {
        $this->sonepackage->pathVariables = array();

        $this->sonepackage->setPathVariables(array(
            'id' => 10,
            'pagetitle' => 'Foo Bar Baz',
            'alias' => 'foo_bar_baz',
            'context_key' => 'test'
        ));

        $this->assertEquals(10, $this->sonepackage->pathVariables['id']);
        $this->assertEquals('Foo Bar Baz', $this->sonepackage->pathVariables['pagetitle']);
        $this->assertEquals('foo_bar_baz', $this->sonepackage->pathVariables['alias']);
        $this->assertEquals('test', $this->sonepackage->pathVariables['context_key']);

        $this->sonepackage->setPathVariables(array(
            'id' => 15,
            'context_key' => 'mgr',
            'foo' => 'bar'
        ));

        $this->assertEquals(15, $this->sonepackage->pathVariables['id']);
        $this->assertEquals('Foo Bar Baz', $this->sonepackage->pathVariables['pagetitle']);
        $this->assertEquals('foo_bar_baz', $this->sonepackage->pathVariables['alias']);
        $this->assertEquals('mgr', $this->sonepackage->pathVariables['context_key']);
        $this->assertEquals('bar', $this->sonepackage->pathVariables['foo']);
    }


    /**
     * Tests resource path placeholders
     *
     * @dataProvider providerParseResourcePathVariables
     *
     * @param modResource $resource
     * @param $path
     * @param $expected
     */
    public function testParseResourcePathVariables(modResource $resource, $path, $expected) {
        global $modx;
        $sonepackage = new sOnePackage($modx);
        // Set the resource
        $sonepackage->setResource($resource);

        // Test the path
        $this->assertEquals($expected, $sonepackage->parsePathVariables($path));
    }

    /**
     * @return array
     */
    public function providerParseResourcePathVariables() {
        // Gotta set variables on the global because the Provider is set up before this class
        /** @var sOnePackage $sonepackage */
        global $sonepackage;

        /** @var modResource $resource */
        $resource = $sonepackage->modx->newObject('modResource');
        $alias = $sonepackage->modx->filterPathSegment('unit_' . date(DATE_ATOM));
        $pagetitle = 'Unit Testing ' . date(DATE_ATOM);
        $resource->fromArray(array(
            'pagetitle' => $pagetitle,
            'description' => 'your-description',
            'alias' => $alias,
            'parent' => 0,
        ));
        $resource->set('id', 99999);

        return array(
            array(
                $resource,
                "assets/[[+alias]]/",
                "assets/$alias/"
            ),
            array(
                $resource,
                "assets/[[+alias]]/[[+pagetitle]]/",
                "assets/$alias/$pagetitle/"
            ),
            array(
                $resource,
                "assets/[[+alias]]/[[+pagetitle]]/[[+id]]/",
                "assets/$alias/$pagetitle/99999/"
            ),
            array(
                $resource,
                "assets/[[+alias]]/[[+pagetitle]]/[[+id]]/[[+description]]/",
                "assets/$alias/$pagetitle/99999/your-description/"
            ),
        );
    }
}
