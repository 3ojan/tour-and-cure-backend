import React, { memo, ReactElement } from 'react';
import { Layout, Menu, Breadcrumb } from 'antd';
import { Header, Sidebar } from 'components/containers';
import { Requests, NewRequests, Home } from 'components/screens';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { Content } from 'antd/lib/layout/layout';
import orderedRoutes from 'lib/routes';
import AuthProtector from 'components/containers/AuthProtector';

console.log(orderedRoutes);
export type routesProps = {};

function RoutesComposition(props: routesProps): ReactElement {
  return (
    <Layout style={{ flex: 1 }}>
      <Header />
      <div
        style={{
          flex: 1,
          display: 'flex',
        }}
      >
        <Sidebar />
        <Content
          className="site-layout-background"
          style={{
            padding: 24,
            margin: 0,
            minHeight: 280,
          }}
        >
          <Routes>
            {orderedRoutes.map(route => {
              const Component = route.element;
              return (
                <Route
                  key={route.name}
                  path={route.path}
                  element={
                    route.isPublic ? (
                      <Component />
                    ) : (
                      <AuthProtector key={`protected_${route.name}`}>
                        <Component />
                      </AuthProtector>
                    )
                  }
                />
              );
            })}
            {/*  <Route path="/" element={<Home />} />
            <Route path="requests" element={<Requests />} />
            <Route path="new-requests" element={<NewRequests />} /> */}
          </Routes>
        </Content>
      </div>
    </Layout>
  );
}

export default memo(RoutesComposition);
