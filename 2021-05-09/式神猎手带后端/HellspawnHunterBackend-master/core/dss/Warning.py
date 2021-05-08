from __future__ import unicode_literals
import warnings

import datetime


class RemovedInNextVersionWarning(DeprecationWarning):
    pass


def remove_check(**kwargs):
    deep = kwargs.get('deep', None)
    if deep is not None:
        warnings.warn('Parameter "deep" will removed in next version!', RemovedInNextVersionWarning, stacklevel=2)
        return deep
    return None