<?php

namespace Framework\Mvc\Views;

use Exception;
use Framework\Base\Autoload;
use Framework\Base\KernelInjector;

/**
 * Class PhpView
 */
class PhpView extends View
{
    /** @var string $layout шаблон */
    public $layout;
    /** @var string $view представление */
    public $view;
    /** @var string $path путь к представлению */
    public $path;
    /** @var string $data переданные данные */
    public $data = '';

    /**
     * Выводит предствление
     */
    public function render()
    {
        if (!$this->view) {
            return false;
        }

        return $this->renderRawData(
            $this->data ?: $this->renderFile($this->getViewFile($this->view), $this->params)
        );
    }


    /**
     * Выводит преставление с данными
     *
     * @param string $data
     * @return string
     * @throws \ReflectionException
     */
    public function renderRawData(string $data = '')
    {
        $layoutPath = null;

        if ($this->layout && (!$layoutPath = $this->getLayoutFile((new KernelInjector)->build()->getAppDir()))
        ) {
            var_dump('не найден шаблон ' . $this->layout);
        }

        if ($layoutPath) {
            $data = $this->renderFile($layoutPath, ['content' => $data]);
        }

        return $data;
    }

    /**
     * Выводит предстваление
     *
     * @param string $fileName
     * @param array $data
     * @return false|string
     */
    protected function renderFile(string $fileName, array $data = [])
    {
        extract($data, EXTR_PREFIX_SAME || EXTR_REFS, 'data');
        ob_start();

        include str_replace('\\', '/', $fileName);

        return ob_get_clean();
    }

    /**
     * Возвращает путь к шаблону
     *
     * @param $appDir
     * @return false|string
     */
    protected function getLayoutFile($appDir)
    {
        $layout = $appDir;
        $afterPath = 'views/layouts/' . $this->layout . '.php';

        if (file_exists($layout . $afterPath)) {
            return $layout . $afterPath;
        }

        if (file_exists($appDir . '/' . $afterPath)) {
            return $appDir . '/' . $afterPath;
        }

        return false;
    }

    /**
     * Возвращает путь к представлению
     *
     * @param string $view
     * @return string
     * @throws \ReflectionException
     */
    private function getViewFile(string $view): string
    {
        $calledClass = $this->path;

        if (0 === strpos($calledClass, 'App')) {
            $path = (new KernelInjector)->build()->getAppDir();
        } else {
            $path = Autoload::getAlias('Micro');
        }

        $cl = strtolower(dirname(str_replace('\\', '/', $calledClass)));
        $cl = substr($cl, strpos($cl, '/'));

        $className = str_replace('controller', '',
            strtolower(basename(str_replace('\\', '/', '/' . $this->path))));
        $path .= dirname($cl) . '/views/' . $className . '/' . $view . '.php';

        $path = str_replace('//', '/', $path);

        if (!file_exists($path)) {
            throw new Exception('Предстваление `' . $path . '` не найдено');
        }

        return $path;
    }
}