# Flickr Album content plugin for Joomla

This is a Joomla! plugin which replace `{flickr album="ALBUMID" user"USERID"}` by the Flickr Album embed.<br>
The user id is optional when it is configured in plugin config.

## Requirements

* Joomla! 5.0 or newer
* PHP 8.0 or newer

## Support

This plugin is primarily designed for use on HKweb projects, and as such, priority is given to the use cases there. Additional features or use cases will be considered on a case-by-case basis.

## HTTPHeaders

When using System Plugin HTTPHeaders with Content Security Policy please add `https://www.flickr.com/` to `frame-src`.

## Flickr Admin ID and User ID

Your Flickr user ID is a unique identifier for your Flickr account. You can find it in several ways:

1. **Via Your Flickr Profile URL**<br>
  Go to your Flickr profile in a web browser. The URL will look like one of these:
    - If you set a custom username:<br>
     `https://www.flickr.com/photos/yourusername/`<br>
      Here, `yourusername` is your Flickr user ID.
    - If you have not set a custom username:<br>
      `https://www.flickr.com/photos/12345678@N00/`<br>
      Here, `12345678@N00` is your Flickr user ID.
2. Find User ID for Any Flickr Album
   1. Visit your Flickr album page.
   2. The URL will look like:<br>
     `https://www.flickr.com/photos/12345678@N00/albums/12345678901234567`
   3. The part after `/photos/` and before `/albums/` is your user ID (`12345678@N00` in this case).
3. Using a Flickr User ID Lookup Tool<br>
   If you only know your Flickr screen name, you can use a tool like:
   -  [Flickr NSID Lookup Tool](https://www.webpagefx.com/tools/idgettr/)<br>
      Enter your Flickr username or profile URL, and it will give you your user ID (also called “NSID”).

### Example
If your album URL is:<br>
`https://www.flickr.com/photos/98765432@N04/albums/72177720301234567`<br>
Then your Flickr user ID is:<br>
`98765432@N04`


### Release steps

- `build/build.sh`
- `git commit -am 'prepare plg_content_fliockr 25.26.0'`
- `git tag -s '25.26.0' -m 'plg_content_flickr 25.26.0'`
- `git push origin --tags`
- create the release on GitHub
- `git push origin main`
