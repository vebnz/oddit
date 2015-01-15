from django.core.exceptions import ValidationError
def validate_pdf(value):
        if not value.read(5) == '%PDF-':
                    raise ValidationError("This file is not in PDF format.")
