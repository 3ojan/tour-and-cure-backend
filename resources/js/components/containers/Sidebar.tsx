import {
  UserOutlined,
  LaptopOutlined,
  NotificationOutlined,
} from '@ant-design/icons';
import { Menu } from 'antd';
import Sider from 'antd/lib/layout/Sider';
import SubMenu from 'antd/lib/menu/SubMenu';
import React, { memo, ReactElement } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import routes, { getIndexByPath } from 'lib/routes';
import { useFirebaseAuth } from 'lib';
import { NodeElement } from 'rc-tree/lib/interface';

export type SidebarProps = {};

function Sidebar(props: SidebarProps): ReactElement | null {
  const navigate = useNavigate();
  const location = useLocation();
  const route = routes[getIndexByPath(location.pathname)];
  const user = useFirebaseAuth();

  if (!user) return null;
  return (
    <div
      style={{
        // flex: '1 0 200px'
        height: '100%',
      }}
    >
      <Sider
        // width={200}
        className="site-layout-background"
      >
        <Menu
          mode="inline"
          defaultSelectedKeys={[route ? route.name : '']}
          selectedKeys={[route ? route.name : '']}
          style={{ height: '100%', borderRight: 0 }}
        >
          {routes
            .filter(route => route.isInSidebar)
            .map(route => (
              <Menu.Item key={route.name} onClick={() => navigate(route.path)}>
                {route.name}
              </Menu.Item>
            ))}
        </Menu>
      </Sider>
    </div>
  );
}

export default memo(Sidebar);
