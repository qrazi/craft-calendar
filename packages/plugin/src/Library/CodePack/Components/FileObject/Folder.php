<?php

namespace Solspace\Calendar\Library\CodePack\Components\FileObject;

use Solspace\Calendar\Calendar;
use Solspace\Calendar\Library\CodePack\Exceptions\FileObject\FileObjectException;
use Symfony\Component\Finder\SplFileInfo;

class Folder extends FileObject implements \Iterator
{
    /** @var null|FileObject[] */
    protected ?array $files = null;

    private ?int $fileCount = null;

    /**
     * Folder constructor.
     */
    protected function __construct(string $path)
    {
        $folder = pathinfo($path, \PATHINFO_BASENAME);

        $this->folder = true;
        $this->path = $path;
        $this->name = $folder;

        /** @var SplFileInfo[] $fileIterator */
        $fileIterator = $this->getFinder()
            ->ignoreDotFiles(true)
            ->depth(0)
            ->in($path)
        ;

        $files = [];
        foreach ($fileIterator as $filePath) {
            $files[] = FileObject::createFromPath($filePath->getPathname());
        }

        $this->files = $files;
    }

    /**
     * Copy the file or directory to $target location.
     *
     * @throws FileObjectException
     */
    public function copy(string $target, ?string $prefix = null, ?callable $callable = null, ?string $filePrefix = null): void
    {
        $target = rtrim($target, '/');
        $targetFolderPath = $target.'/'.$filePrefix.$this->name;
        if (!file_exists($targetFolderPath)) {
            $this->getFileSystem()->mkdir($targetFolderPath);

            if (!file_exists($targetFolderPath)) {
                throw new FileObjectException(
                    \sprintf(
                        'Permissions denied. Could not create a folder in "%s".<br>Check how to solve this problem <a href="%s">here</a>',
                        $targetFolderPath,
                        Calendar::PERMISSIONS_HELP_LINK
                    )
                );
            }
        }

        foreach ($this->files as $file) {
            $file->copy($targetFolderPath, $prefix, $callable);
        }
    }

    /**
     * Gets the total number of File instances this Folder object has, recursively.
     */
    public function getFileCount(): int
    {
        if (null === $this->fileCount) {
            $count = 0;
            foreach ($this->files as $file) {
                if ($file instanceof self) {
                    $count += $file->getFileCount();
                } else {
                    ++$count;
                }
            }

            $this->fileCount = $count;
        }

        return $this->fileCount;
    }

    public function getFiles(): ?array
    {
        return $this->files;
    }

    /**
     * Return the current element.
     *
     * @see  http://php.net/manual/en/iterator.current.php
     *
     * @return mixed can return any type
     *
     * @since 5.0.0
     */
    public function current(): mixed
    {
        return current($this->files);
    }

    /**
     * Move forward to next element.
     *
     * @see  http://php.net/manual/en/iterator.next.php
     * @since 5.0.0
     */
    public function next(): void
    {
        next($this->files);
    }

    /**
     * Return the key of the current element.
     *
     * @see  http://php.net/manual/en/iterator.key.php
     *
     * @return mixed scalar on success, or null on failure
     *
     * @since 5.0.0
     */
    public function key(): mixed
    {
        return key($this->files);
    }

    /**
     * Checks if current position is valid.
     *
     * @see  http://php.net/manual/en/iterator.valid.php
     *
     * @return bool The return value will be casted to boolean and then evaluated.
     *              Returns true on success or false on failure.
     *
     * @since 5.0.0
     */
    public function valid(): bool
    {
        return null !== $this->key() && false !== $this->key();
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @see  http://php.net/manual/en/iterator.rewind.php
     * @since 5.0.0
     */
    public function rewind(): void
    {
        reset($this->files);
    }
}
