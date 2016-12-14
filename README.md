# S3 Backend for [Kunstmaan Static Site Bundle](https://github.com/mlebkowski/kunstmaan-static-site-bundle)

## Installation

`composer require nassau/kunstmaan-static-site-s3-backend`

## Configuration

```yaml
# app/config.yml

kunstmaan_static_site_s3_backend:
    access_key: AWS_ACCESS_KEY
    access_secret: AWS_ACCESS_SECRET
    buckets:
        default: 
            bucket_name: foobar-bucket
            target_path: /
            acl: public-read
        other_bucket:
            access_key: CUSTOM_AWS_ACCESS_KEY
            access_secret: CUSTOM_AWS_ACCESS_SECRET
            bucket_name: other-bucket
            target_path: /docs
```

## Usage

You can now export your static site directly to a S3 bucket using `s3:` scheme

```
app/console nassau:static-site:dump s3:
```

The "default" bucket values will be used, but you can override them if you want.

```
app/console nassau:static-site:dump s3://key:secret@bucket-name-or-config-name/custom-target-path
```
