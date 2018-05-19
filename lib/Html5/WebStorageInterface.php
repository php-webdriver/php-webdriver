<?php

namespace Facebook\WebDriver\Html5;

/**
 * Represents browser storage for the current site.
 */
interface WebStorageInterface
{
    /**
     * Get the local storage for the site currently opened in the browser.
     *
     * @return LocalStorageInterface
     */
    public function getLocalStorage();

    /**
     * Get the session storage for the site currently opened in the browser.
     *
     * @return SessionStorageInterface
     */
    public function getSessionStorage();
}
