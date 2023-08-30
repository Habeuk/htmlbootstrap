<?

namespace Stephane888\HtmlBootstrap;

class HelpMigrate {

    protected $patchResolver;
    public static function getPatch($type, $name) {
        if (!$this->patchResolver) {
            $this->pathResolver = \Drupal::service('extension.path.resolver');
        }
        return $this->patchResolver->getPatch($type, $name);
    }
}
