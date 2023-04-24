import { Button } from 'antd';
import React, { memo, ReactElement } from 'react';
import { auth } from 'lib';
import { useTranslation } from 'react-i18next';

export type AccountProps = {};

function Account(props: AccountProps): ReactElement {
  const { t } = useTranslation();
  return (
    <div
      style={{
        justifyContent: 'center',
        display: 'flex',
      }}
    >
      <Button
        type="primary"
        danger
        onClick={() => {
          auth.signOut();
        }}
      >
        {t('account.logout')}
      </Button>
    </div>
  );
}

export default memo(Account);
