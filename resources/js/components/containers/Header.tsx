import React, { memo, ReactElement } from 'react';
import { PageHeader, Button, Descriptions } from 'antd';
import { useNavigate, useLocation, useNavigationType } from 'react-router-dom';
import { UserOutlined } from '@ant-design/icons';
import routes, { getIndexByPath } from 'lib/routes';

export type HeaderProps = {};

function Header(props: HeaderProps): ReactElement {
  const navigate = useNavigate();
  const location = useLocation();
  const route = routes[getIndexByPath(location.pathname)];
  return (
    <PageHeader
      ghost={false}
      backIcon={false}
      title={route ? route.name : ''}
      // subTitle="This is a subtitle"
      style={{
        boxShadow: '1px 1px 6px 2px #dfdfdf',
        zIndex: '9',
      }}
      extra={[
        <Button
          key="header_user_acc"
          type="primary"
          shape="circle"
          icon={<UserOutlined />}
          onClick={() => navigate('/account')}
        />,
      ]}
    ></PageHeader>
  );
}

export default memo(Header);
